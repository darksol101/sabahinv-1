/*
 * see COPYRIGHT
 */

#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <sys/types.h>
#include <sys/stat.h>
#include <fcntl.h>
#include <time.h>
#include <ctype.h>
#include <math.h>

#ifndef WINDOWS
#	include <netinet/in.h>
#	include <unistd.h>
#else
#	include "windows.h"
#endif

#include "ttf.h"
#include "pt1.h"
#include "global.h"

/* big and small values for comparisons */
#define FBIGVAL	(1e20)
#define FEPS	(100000./FBIGVAL)

/* names of the axes */
#define X	0
#define	Y	1

/* the GENTRY extension structure used in fforceconcise() */

struct gex_con {
	double d[2 /*X, Y*/]; /* sizes of curve */
	double sin2; /* squared sinus of the angle to the next gentry */
	double len2; /* squared distance between the endpoints */

/* number of reference dots taken from each curve */
#define NREFDOTS	3

	double dots[NREFDOTS][2]; /* reference dots */

	int flags; /* flags for gentry and tits joint to the next gentry */
/* a vertical or horizontal line may be in 2 quadrants at once */
#define GEXF_QUL	0x00000001 /* in up-left quadrant */
#define GEXF_QUR	0x00000002 /* in up-right quadrant */
#define GEXF_QDR	0x00000004 /* in down-right quadrant */
#define GEXF_QDL	0x00000008 /* in down-left quadrant */
#define GEXF_QMASK	0x0000000F /* quadrant mask */

/* if a line is nearly vertical or horizontal, we remember that idealized quartant too */
#define GEXF_QTO_IDEAL(f)	(((f)&0xF)<<4)
#define GEXF_QFROM_IDEAL(f)	(((f)&0xF0)>>4)
#define GEXF_IDQ_L	0x00000090 /* left */
#define GEXF_IDQ_R	0x00000060 /* right */
#define GEXF_IDQ_U	0x00000030 /* up */
#define GEXF_IDQ_D	0x000000C0 /* down */

/* possibly can be joined with conditions: 
 * (in order of increasing preference, the numeric order is important) 
 */
#define GEXF_JLINE	0x00000100 /* into one line */
#define GEXF_JIGN	0x00000200 /* if one entry's tangent angle is ignored */
#define GEXF_JID	0x00000400 /* if one entry is idealized to hor/vert */
#define GEXF_JFLAT	0x00000800 /* if one entry is flattened */
#define GEXF_JGOOD	0x00001000 /* perfectly, no additional maodifications */

#define GEXF_JMASK	0x00001F00 /* the mask of all above */
#define GEXF_JCVMASK	0x00001E00 /* the mask of all above except JLINE */

/* which entry needs to be modified for conditional joining */
#define GEXF_JIGN1	0x00002000
#define GEXF_JIGN2	0x00004000
#define GEXF_JIGNDIR(dir)	(GEXF_JIGN1<<(dir))
#define GEXF_JID1	0x00008000
#define GEXF_JID2	0x00010000
#define GEXF_JIDDIR(dir)	(GEXF_JID1<<(dir))
#define GEXF_JFLAT1	0x00020000
#define GEXF_JFLAT2	0x00040000
#define GEXF_JFLATDIR(dir)	(GEXF_JFLAT1<<(dir))

#define GEXF_VERT	0x00100000 /* is nearly vertical */
#define GEXF_HOR	0x00200000 /* is nearly horizontal */
#define GEXF_FLAT	0x00400000 /* is nearly flat */

#define GEXF_VDOTS	0x01000000 /* the dots are valid */

	signed char isd[2 /*X,Y*/]; /* signs of the sizes */
};
typedef struct gex_con GEX_CON;

/* convenience macros */
#define	X_CON(ge)	((GEX_CON *)((ge)->ext))
#define X_CON_D(ge)	(X_CON(ge)->d)
#define X_CON_DX(ge)	(X_CON(ge)->d[0])
#define X_CON_DY(ge)	(X_CON(ge)->d[1])
#define X_CON_ISD(ge)	(X_CON(ge)->isd)
#define X_CON_ISDX(ge)	(X_CON(ge)->isd[0])
#define X_CON_ISDY(ge)	(X_CON(ge)->isd[1])
#define X_CON_SIN2(ge)	(X_CON(ge)->sin2)
#define X_CON_LEN2(ge)	(X_CON(ge)->len2)
#define X_CON_F(ge)	(X_CON(ge)->flags)

/* performance statistics about guessing the concise curves */
static int ggoodcv=0, ggoodcvdots=0, gbadcv=0, gbadcvdots=0;

int      stdhw, stdvw;	/* dominant stems widths */
int      stemsnaph[12], stemsnapv[12];	/* most typical stem width */

int      bluevalues[14];
int      nblues;
int      otherblues[10];
int      notherb;
int      bbox[4];	/* the FontBBox array */
double   italic_angle;

GLYPH   *glyph_list;
int    encoding[ENCTABSZ];	/* inverse of glyph[].char_no */
int    kerning_pairs = 0;

/* prototypes */
static void fixcvdir( GENTRY * ge, int dir);
static void fixcvends( GENTRY * ge);
static int fgetcvdir( GENTRY * ge);
static int igetcvdir( GENTRY * ge);
static int fiszigzag( GENTRY *ge);
static int iiszigzag( GENTRY *ge);
static GENTRY * freethisge( GENTRY *ge);
static void addgeafter( GENTRY *oge, GENTRY *nge );
static GENTRY * newgentry( int flags);
static void debugstems( char *name, STEM * hstems, int nhs, STEM * vstems, int nvs);
static int addbluestems( STEM *s, int n);
static void sortstems( STEM * s, int n);
static int stemoverlap( STEM * s1, STEM * s2);
static int steminblue( STEM *s);
static void markbluestems( STEM *s, int nold);
static int joinmainstems( STEM * s, int nold, int useblues);
static void joinsubstems( STEM * s, short *pairs, int nold, int useblues);
static void fixendpath( GENTRY *ge);
static void fdelsmall( GLYPH *g, double minlen);
static void alloc_gex_con( GENTRY *ge);
static double fjointsin2( GENTRY *ge1, GENTRY *ge2);
static double fcvarea( GENTRY *ge);
static double fcvval( GENTRY *ge, int axis, double t);
static void fsampledots( GENTRY *ge, double dots[][2], int ndots);
static void fnormalizege( GENTRY *ge);
static void fanalyzege( GENTRY *ge);
static void fanalyzejoint( GENTRY *ge);
static void fconcisecontour( GLYPH *g, GENTRY *ge);
static double fclosegap( GENTRY *from, GENTRY *to, int axis,
	double gap, double *ret);

int
isign(
     int x
)
{
	if (x > 0)
		return 1;
	else if (x < 0)
		return -1;
	else
		return 0;
}

int
fsign(
     double x
)
{
	if (x > 0.0)
		return 1;
	else if (x < 0.0)
		return -1;
	else
		return 0;
}

static GENTRY *
newgentry(
	int flags
)
{
	GENTRY         *ge;

	ge = calloc(1, sizeof(GENTRY));

	if (ge == 0) {
		fprintf(stderr, "***** Memory allocation error *****\n");
		exit(255);
	}
	ge->stemid = -1;
	ge->flags = flags;
	/* the rest is set to 0 by calloc() */
	return ge;
}

/*
 * Routines to print out Postscript functions with optimization
 */

void
rmoveto(
	int dx,
	int dy
)
{
	if (optimize && dx == 0)
		fprintf(pfa_file, "%d vmoveto\n", dy);
	else if (optimize && dy == 0)
		fprintf(pfa_file, "%d hmoveto\n", dx);
	else
		fprintf(pfa_file, "%d %d rmoveto\n", dx, dy);
}

void
rlineto(
	int dx,
	int dy
)
{
	if (optimize && dx == 0 && dy == 0)	/* for special pathologic
						 * case */
		return;
	else if (optimize && dx == 0)
		fprintf(pfa_file, "%d vlineto\n", dy);
	else if (optimize && dy == 0)
		fprintf(pfa_file, "%d hlineto\n", dx);
	else
		fprintf(pfa_file, "%d %d rlineto\n", dx, dy);
}

void
rrcurveto(
	  int dx1,
	  int dy1,
	  int dx2,
	  int dy2,
	  int dx3,
	  int dy3
)
{
	/* first two ifs are for crazy cases that occur surprisingly often */
	if (optimize && dx1 == 0 && dx2 == 0 && dx3 == 0)
		rlineto(0, dy1 + dy2 + dy3);
	else if (optimize && dy1 == 0 && dy2 == 0 && dy3 == 0)
		rlineto(dx1 + dx2 + dx3, 0);
	else if (optimize && dy1 == 0 && dx3 == 0)
		fprintf(pfa_file, "%d %d %d %d hvcurveto\n",
			dx1, dx2, dy2, dy3);
	else if (optimize && dx1 == 0 && dy3 == 0)
		fprintf(pfa_file, "%d %d %d %d vhcurveto\n",
			dy1, dx2, dy2, dx3);
	else
		fprintf(pfa_file, "%d %d %d %d %d %d rrcurveto\n",
			dx1, dy1, dx2, dy2, dx3, dy3);
}

void
closepath(void)
{
	fprintf(pfa_file, "closepath\n");
}

/*
 * Many of the path processing routines exist (or will exist) in
 * both floating-point and integer version. Fimally most of the
 * processing will go in floating point and the integer processing
 * will become legacy.
 * The names of floating routines start with f, names of integer 
 * routines start with i, and those old routines existing in one 
 * version only have no such prefix at all.
 */

/*
** Routine that checks integrity of the path, for debugging
*/

void
assertpath(
	   GENTRY * from,
	   char *file,
	   int line,
	   char *name
)
{
	GENTRY         *first, *pe, *ge;
	int	isfloat;

	if(from==0)
		return;
	isfloat = (from->flags & GEF_FLOAT);
	pe = from->prev;
	for (ge = from; ge != 0; pe = ge, ge = ge->next) {
		if( (ge->flags & GEF_FLOAT) ^ isfloat ) {
			fprintf(stderr, "**! assertpath: called from %s line %d (%s) ****\n", file, line, name);
			fprintf(stderr, "float flag changes from %s to %s at 0x%p (type %c, prev type %c)\n",
				(isfloat ? "TRUE" : "FALSE"), (isfloat ? "FALSE" : "TRUE"), ge, ge->type, pe->type);
			abort();
		}
		if (pe != ge->prev) {
			fprintf(stderr, "**! assertpath: called from %s line %d (%s) ****\n", file, line, name);
			fprintf(stderr, "unidirectional chain 0x%x -next-> 0x%x -prev-> 0x%x \n",
				pe, ge, ge->prev);
			abort();
		}

		switch(ge->type) {
		case GE_MOVE:
			break;
		case GE_PATH:
			if (ge->prev == 0) {
				fprintf(stderr, "**! assertpath: called from %s line %d (%s) ****\n", file, line, name);
				fprintf(stderr, "empty path at 0x%x \n", ge);
				abort();
			}
			break;
		case GE_LINE:
		case GE_CURVE:
			if(ge->frwd->bkwd != ge) {
				fprintf(stderr, "**! assertpath: called from %s line %d (%s) ****\n", file, line, name);
				fprintf(stderr, "unidirectional chain 0x%x -frwd-> 0x%x -bkwd-> 0x%x \n",
					ge, ge->frwd, ge->frwd->bkwd);
				abort();
			}
			if(ge->prev->type == GE_MOVE) {
				first = ge;
				if(ge->bkwd->next->type != GE_PATH) {
					fprintf(stderr, "**! assertpath: called from %s line %d (%s) ****\n", file, line, name);
					fprintf(stderr, "broken first backlink 0x%x -bkwd-> 0x%x -next-> 0x%x \n",
						ge, ge->bkwd, ge->bkwd->next);
					abort();
				}
			}
			if(ge->next->type == GE_PATH) {
				if(ge->frwd != first) {
					fprintf(stderr, "**! assertpath: called from %s line %d (%s) ****\n", file, line, name);
					fprintf(stderr, "broken loop 0x%x -...-> 0x%x -frwd-> 0x%x \n",
						first, ge, ge->frwd);
					abort();
				}
			}
			break;
		}

	}
}

void
assertisfloat(
	GLYPH *g,
	char *msg
)
{
	if( !(g->flags & GF_FLOAT) ) {
		fprintf(stderr, "**! Glyph %s is not float: %s\n", g->name, msg);
		abort();
	}
	if(g->lastentry) {
		if( !(g->lastentry->flags & GEF_FLOAT) ) {
			fprintf(stderr, "**! Glyphs %s last entry is int: %s\n", g->name, msg);
			abort();
		}
	}
}

void
assertisint(
	GLYPH *g,
	char *msg
)
{
	if( (g->flags & GF_FLOAT) ) {
		fprintf(stderr, "**! Glyph %s is not int: %s\n", g->name, msg);
		abort();
	}
	if(g->lastentry) {
		if( (g->lastentry->flags & GEF_FLOAT) ) {
			fprintf(stderr, "**! Glyphs %s last entry is float: %s\n", g->name, msg);
			abort();
		}
	}
}


/*
 * Routines to save the generated data about glyph
 */

void
fg_rmoveto(
	  GLYPH * g,
	  double x,
	  double y)
{
	GENTRY         *oge;

	if (ISDBG(BUILDG))
		fprintf(stderr, "%s: f rmoveto(%g, %g)\n", g->name, x, y);

	assertisfloat(g, "adding float MOVE");

	if ((oge = g->lastentry) != 0) {
		if (oge->type == GE_MOVE) {	/* just eat up the first move */
			oge->fx3 = x;
			oge->fy3 = y;
		} else if (oge->type == GE_LINE || oge->type == GE_CURVE) {
			fprintf(stderr, "Glyph %s: MOVE in middle of path\n", g->name);
		} else {
			GENTRY         *nge;

			nge = newgentry(GEF_FLOAT);
			nge->type = GE_MOVE;
			nge->fx3 = x;
			nge->fy3 = y;

			oge->next = nge;
			nge->prev = oge;
			g->lastentry = nge;
		}
	} else {
		GENTRY         *nge;

		nge = newgentry(GEF_FLOAT);
		nge->type = GE_MOVE;
		nge->fx3 = x;
		nge->fy3 = y;
		nge->bkwd = (GENTRY*)&g->entries;
		g->entries = g->lastentry = nge;
	}

	if (0 && ISDBG(BUILDG))
		dumppaths(g, NULL, NULL);
}

void
ig_rmoveto(
	  GLYPH * g,
	  int x,
	  int y)
{
	GENTRY         *oge;

	if (ISDBG(BUILDG))
		fprintf(stderr, "%s: i rmoveto(%d, %d)\n", g->name, x, y);

	assertisint(g, "adding int MOVE");

	if ((oge = g->lastentry) != 0) {
		if (oge->type == GE_MOVE) {	/* just eat up the first move */
			oge->ix3 = x;
			oge->iy3 = y;
		} else if (oge->type == GE_LINE || oge->type == GE_CURVE) {
			fprintf(stderr, "Glyph %s: MOVE in middle of path, ignored\n", g->name);
		} else {
			GENTRY         *nge;

			nge = newgentry(0);
			nge->type = GE_MOVE;
			nge->ix3 = x;
			nge->iy3 = y;

			oge->next = nge;
			nge->prev = oge;
			g->lastentry = nge;
		}
	} else {
		GENTRY         *nge;

		nge = newgentry(0);
		nge->type = GE_MOVE;
		nge->ix3 = x;
		nge->iy3 = y;
		nge->bkwd = (GENTRY*)&g->entries;
		g->entries = g->lastentry = nge;
	}

}

void
fg_rlineto(
	  GLYPH * g,
	  double x,
	  double y)
{
	GENTRY         *oge, *nge;

	if (ISDBG(BUILDG))
		fprintf(stderr, "%s: f rlineto(%g, %g)\n", g->name, x, y);

	assertisfloat(g, "adding float LINE");

	nge = newgentry(GEF_FLOAT);
	nge->type = GE_LINE;
	nge->fx3 = x;
	nge->fy3 = y;

	if ((oge = g->lastentry) != 0) {
		if (x == oge->fx3 && y == oge->fy3) {	/* empty line */
			/* ignore it or we will get in troubles later */
			free(nge);
			return;
		}
		if (g->path == 0) {
			g->path = nge;
			nge->bkwd = nge->frwd = nge;
		} else {
			oge->frwd = nge;
			nge->bkwd = oge;
			g->path->bkwd = nge;
			nge->frwd = g->path;
		}

		oge->next = nge;
		nge->prev = oge;
		g->lastentry = nge;
	} else {
		WARNING_1 fprintf(stderr, "Glyph %s: LINE outside of path\n", g->name);
		free(nge);
	}

	if (0 && ISDBG(BUILDG))
		dumppaths(g, NULL, NULL);
}

void
ig_rlineto(
	  GLYPH * g,
	  int x,
	  int y)
{
	GENTRY         *oge, *nge;

	if (ISDBG(BUILDG))
		fprintf(stderr, "%s: i rlineto(%d, %d)\n", g->name, x, y);

	assertisint(g, "adding int LINE");

	nge = newgentry(0);
	nge->type = GE_LINE;
	nge->ix3 = x;
	nge->iy3 = y;

	if ((oge = g->lastentry) != 0) {
		if (x == oge->ix3 && y == oge->iy3) {	/* empty line */
			/* ignore it or we will get in troubles later */
			free(nge);
			return;
		}
		if (g->path == 0) {
			g->path = nge;
			nge->bkwd = nge->frwd = nge;
		} else {
			oge->frwd = nge;
			nge->bkwd = oge;
			g->path->bkwd = nge;
			nge->frwd = g->path;
		}

		oge->next = nge;
		nge->prev = oge;
		g->lastentry = nge;
	} else {
		WARNING_1 fprintf(stderr, "Glyph %s: LINE outside of path\n", g->name);
		free(nge);
	}

}

void
fg_rrcurveto(
	    GLYPH * g,
	    double x1,
	    double y1,
	    double x2,
	    double y2,
	    double x3,
	    double y3)
{
	GENTRY         *oge, *nge;

	oge = g->lastentry;

	if (ISDBG(BUILDG))
		fprintf(stderr, "%s: f rrcurveto(%g, %g, %g, %g, %g, %g)\n"
			,g->name, x1, y1, x2, y2, x3, y3);

	assertisfloat(g, "adding float CURVE");

	if (oge && oge->fx3 == x1 && x1 == x2 && x2 == x3)	/* check if it's
								 * actually a line */
		fg_rlineto(g, x1, y3);
	else if (oge && oge->fy3 == y1 && y1 == y2 && y2 == y3)
		fg_rlineto(g, x3, y1);
	else {
		nge = newgentry(GEF_FLOAT);
		nge->type = GE_CURVE;
		nge->fx1 = x1;
		nge->fy1 = y1;
		nge->fx2 = x2;
		nge->fy2 = y2;
		nge->fx3 = x3;
		nge->fy3 = y3;

		if (oge != 0) {
			if (x3 == oge->fx3 && y3 == oge->fy3) {
				free(nge);	/* consider this curve empty */
				/* ignore it or we will get in troubles later */
				return;
			}
			if (g->path == 0) {
				g->path = nge;
				nge->bkwd = nge->frwd = nge;
			} else {
				oge->frwd = nge;
				nge->bkwd = oge;
				g->path->bkwd = nge;
				nge->frwd = g->path;
			}

			oge->next = nge;
			nge->prev = oge;
			g->lastentry = nge;
		} else {
			WARNING_1 fprintf(stderr, "Glyph %s: CURVE outside of path\n", g->name);
			free(nge);
		}
	}

	if (0 && ISDBG(BUILDG))
		dumppaths(g, NULL, NULL);
}

void
ig_rrcurveto(
	    GLYPH * g,
	    int x1,
	    int y1,
	    int x2,
	    int y2,
	    int x3,
	    int y3)
{
	GENTRY         *oge, *nge;

	oge = g->lastentry;

	if (ISDBG(BUILDG))
		fprintf(stderr, "%s: i rrcurveto(%d, %d, %d, %d, %d, %d)\n"
			,g->name, x1, y1, x2, y2, x3, y3);

	assertisint(g, "adding int CURVE");

	if (oge && oge->ix3 == x1 && x1 == x2 && x2 == x3)	/* check if it's
								 * actually a line */
		ig_rlineto(g, x1, y3);
	else if (oge && oge->iy3 == y1 && y1 == y2 && y2 == y3)
		ig_rlineto(g, x3, y1);
	else {
		nge = newgentry(0);
		nge->type = GE_CURVE;
		nge->ix1 = x1;
		nge->iy1 = y1;
		nge->ix2 = x2;
		nge->iy2 = y2;
		nge->ix3 = x3;
		nge->iy3 = y3;

		if (oge != 0) {
			if (x3 == oge->ix3 && y3 == oge->iy3) {
				free(nge);	/* consider this curve empty */
				/* ignore it or we will get in troubles later */
				return;
			}
			if (g->path == 0) {
				g->path = nge;
				nge->bkwd = nge->frwd = nge;
			} else {
				oge->frwd = nge;
				nge->bkwd = oge;
				g->path->bkwd = nge;
				nge->frwd = g->path;
			}

			oge->next = nge;
			nge->prev = oge;
			g->lastentry = nge;
		} else {
			WARNING_1 fprintf(stderr, "Glyph %s: CURVE outside of path\n", g->name);
			free(nge);
		}
	}
}

void
g_closepath(
	    GLYPH * g
)
{
	GENTRY         *oge, *nge;

	if (ISDBG(BUILDG))
		fprintf(stderr, "%s: closepath\n", g->name);

	oge = g->lastentry;

	if (g->path == 0) {
		WARNING_1 fprintf(stderr, "Warning: **** closepath on empty path in glyph \"%s\" ****\n",
			g->name);
		if (oge == 0) {
			WARNING_1 fprintf(stderr, "No previois entry\n");
		} else {
			WARNING_1 fprintf(stderr, "Previous entry type: %c\n", oge->type);
			if (oge->type == GE_MOVE) {
				g->lastentry = oge->prev;
				if (oge->prev == 0)
					g->entries = 0;
				else
					g->lastentry->next = 0;
				free(oge);
			}
		}
		return;
	}

	nge = newgentry(oge->flags & GEF_FLOAT); /* keep the same type */
	nge->type = GE_PATH;

	g->path = 0;

	oge->next = nge;
	nge->prev = oge;
	g->lastentry = nge;

	if (0 && ISDBG(BUILDG))
		dumppaths(g, NULL, NULL);
}

/*
 * * SB * Routines to smooth and fix the glyphs
 */

/*
** we don't want to see the curves with coinciding middle and
** outer points
*/

static void
fixcvends(
	  GENTRY * ge
)
{
	int             dx, dy;
	int             x0, y0, x1, y1, x2, y2, x3, y3;

	if (ge->type != GE_CURVE)
		return;

	if(ge->flags & GEF_FLOAT) {
		fprintf(stderr, "**! fixcvends(0x%x) on floating entry, ABORT\n", ge);
		abort(); /* dump core */
	}

	x0 = ge->prev->ix3;
	y0 = ge->prev->iy3;
	x1 = ge->ix1;
	y1 = ge->iy1;
	x2 = ge->ix2;
	y2 = ge->iy2;
	x3 = ge->ix3;
	y3 = ge->iy3;


	/* look at the start of the curve */
	if (x1 == x0 && y1 == y0) {
		dx = x2 - x1;
		dy = y2 - y1;

		if (dx == 0 && dy == 0
		    || x2 == x3 && y2 == y3) {
			/* Oops, we actually have a straight line */
			/*
			 * if it's small, we hope that it will get optimized
			 * later
			 */
			if (abs(x3 - x0) <= 2 || abs(y3 - y0) <= 2) {
				ge->ix1 = x3;
				ge->iy1 = y3;
				ge->ix2 = x0;
				ge->iy2 = y0;
			} else {/* just make it a line */
				ge->type = GE_LINE;
			}
		} else {
			if (abs(dx) < 4 && abs(dy) < 4) {	/* consider it very
								 * small */
				ge->ix1 = x2;
				ge->iy1 = y2;
			} else if (abs(dx) < 8 && abs(dy) < 8) {	/* consider it small */
				ge->ix1 += dx / 2;
				ge->iy1 += dy / 2;
			} else {
				ge->ix1 += dx / 4;
				ge->iy1 += dy / 4;
			}
			/* make sure that it's still on the same side */
			if (abs(x3 - x0) * abs(dy) < abs(y3 - y0) * abs(dx)) {
				if (abs(x3 - x0) * abs(ge->iy1 - y0) > abs(y3 - y0) * abs(ge->ix1 - x0))
					ge->ix1 += isign(dx);
			} else {
				if (abs(x3 - x0) * abs(ge->iy1 - y0) < abs(y3 - y0) * abs(ge->ix1 - x0))
					ge->iy1 += isign(dy);
			}

			ge->ix2 += (x3 - x2) / 8;
			ge->iy2 += (y3 - y2) / 8;
			/* make sure that it's still on the same side */
			if (abs(x3 - x0) * abs(y3 - y2) < abs(y3 - y0) * abs(x3 - x2)) {
				if (abs(x3 - x0) * abs(y3 - ge->iy2) > abs(y3 - y0) * abs(x3 - ge->ix2))
					ge->iy1 -= isign(y3 - y2);
			} else {
				if (abs(x3 - x0) * abs(y3 - ge->iy2) < abs(y3 - y0) * abs(x3 - ge->ix2))
					ge->ix1 -= isign(x3 - x2);
			}

		}
	} else if (x2 == x3 && y2 == y3) {
		dx = x1 - x2;
		dy = y1 - y2;

		if (dx == 0 && dy == 0) {
			/* Oops, we actually have a straight line */
			/*
			 * if it's small, we hope that it will get optimized
			 * later
			 */
			if (abs(x3 - x0) <= 2 || abs(y3 - y0) <= 2) {
				ge->ix1 = x3;
				ge->iy1 = y3;
				ge->ix2 = x0;
				ge->iy2 = y0;
			} else {/* just make it a line */
				ge->type = GE_LINE;
			}
		} else {
			if (abs(dx) < 4 && abs(dy) < 4) {	/* consider it very
								 * small */
				ge->ix2 = x1;
				ge->iy2 = y1;
			} else if (abs(dx) < 8 && abs(dy) < 8) {	/* consider it small */
				ge->ix2 += dx / 2;
				ge->iy2 += dy / 2;
			} else {
				ge->ix2 += dx / 4;
				ge->iy2 += dy / 4;
			}
			/* make sure that it's still on the same side */
			if (abs(x3 - x0) * abs(dy) < abs(y3 - y0) * abs(dx)) {
				if (abs(x3 - x0) * abs(ge->iy2 - y3) > abs(y3 - y0) * abs(ge->ix2 - x3))
					ge->ix2 += isign(dx);
			} else {
				if (abs(x3 - x0) * abs(ge->iy2 - y3) < abs(y3 - y0) * abs(ge->ix2 - x3))
					ge->iy2 += isign(dy);
			}

			ge->ix1 += (x0 - x1) / 8;
			ge->iy1 += (y0 - y1) / 8;
			/* make sure that it's still on the same side */
			if (abs(x3 - x0) * abs(y0 - y1) < abs(y3 - y0) * abs(x0 - x1)) {
				if (abs(x3 - x0) * abs(y0 - ge->iy1) > abs(y3 - y0) * abs(x0 - ge->ix1))
					ge->iy1 -= isign(y0 - y1);
			} else {
				if (abs(x3 - x0) * abs(y0 - ge->iy1) < abs(y3 - y0) * abs(x0 - ge->ix1))
					ge->ix1 -= isign(x0 - x1);
			}

		}
	}
}

/*
** After transformations we want to make sure that the resulting
** curve is going in the same quadrant as the original one,
** because rounding errors introduced during transformations
** may make the result completeley wrong.
**
** `dir' argument describes the direction of the original curve,
** it is the superposition of two values for the front and
** rear ends of curve:
**
** >EQUAL - goes over the line connecting the ends
** =EQUAL - coincides with the line connecting the ends
** <EQUAL - goes under the line connecting the ends
**
** See CVDIR_* for exact definitions.
*/

static void
fixcvdir(
	 GENTRY * ge,
	 int dir
)
{
	int             a, b, c, d;
	double          kk, kk1, kk2;
	int             changed;
	int             fdir, rdir;

	if(ge->flags & GEF_FLOAT) {
		fprintf(stderr, "**! fixcvdir(0x%x) on floating entry, ABORT\n", ge);
		abort(); /* dump core */
	}

	fdir = (dir & CVDIR_FRONT) - CVDIR_FEQUAL;
	if ((dir & CVDIR_REAR) == CVDIR_RSAME)
		rdir = fdir; /* we need only isign, exact value doesn't matter */
	else
		rdir = (dir & CVDIR_REAR) - CVDIR_REQUAL;

	fixcvends(ge);

	c = isign(ge->ix3 - ge->prev->ix3);	/* note the direction of
						 * curve */
	d = isign(ge->iy3 - ge->prev->iy3);

	a = ge->iy3 - ge->prev->iy3;
	b = ge->ix3 - ge->prev->ix3;
	kk = fabs(a == 0 ? (b == 0 ? 1. : 100000.) : ((double) b / (double) a));
	a = ge->iy1 - ge->prev->iy3;
	b = ge->ix1 - ge->prev->ix3;
	kk1 = fabs(a == 0 ? (b == 0 ? 1. : 100000.) : ((double) b / (double) a));
	a = ge->iy3 - ge->iy2;
	b = ge->ix3 - ge->ix2;
	kk2 = fabs(a == 0 ? (b == 0 ? 1. : 100000.) : ((double) b / (double) a));

	changed = 1;
	while (changed) {
		if (ISDBG(FIXCVDIR)) {
			/* for debugging */
			fprintf(stderr, "fixcvdir %d %d (%d %d %d %d %d %d) %f %f %f\n",
				fdir, rdir,
				ge->ix1 - ge->prev->ix3,
				ge->iy1 - ge->prev->iy3,
				ge->ix2 - ge->ix1,
				ge->iy2 - ge->iy1,
				ge->ix3 - ge->ix2,
				ge->iy3 - ge->iy2,
				kk1, kk, kk2);
		}
		changed = 0;

		if (fdir > 0) {
			if (kk1 > kk) {	/* the front end has problems */
				if (c * (ge->ix1 - ge->prev->ix3) > 0) {
					ge->ix1 -= c;
					changed = 1;
				} if (d * (ge->iy2 - ge->iy1) > 0) {
					ge->iy1 += d;
					changed = 1;
				}
				/* recalculate the coefficients */
				a = ge->iy3 - ge->prev->iy3;
				b = ge->ix3 - ge->prev->ix3;
				kk = fabs(a == 0 ? (b == 0 ? 1. : 100000.) : ((double) b / (double) a));
				a = ge->iy1 - ge->prev->iy3;
				b = ge->ix1 - ge->prev->ix3;
				kk1 = fabs(a == 0 ? (b == 0 ? 1. : 100000.) : ((double) b / (double) a));
			}
		} else if (fdir < 0) {
			if (kk1 < kk) {	/* the front end has problems */
				if (c * (ge->ix2 - ge->ix1) > 0) {
					ge->ix1 += c;
					changed = 1;
				} if (d * (ge->iy1 - ge->prev->iy3) > 0) {
					ge->iy1 -= d;
					changed = 1;
				}
				/* recalculate the coefficients */
				a = ge->iy1 - ge->prev->iy3;
				b = ge->ix1 - ge->prev->ix3;
				kk1 = fabs(a == 0 ? (b == 0 ? 1. : 100000.) : ((double) b / (double) a));
				a = ge->iy3 - ge->prev->iy3;
				b = ge->ix3 - ge->prev->ix3;
				kk = fabs(a == 0 ? (b == 0 ? 1. : 100000.) : ((double) b / (double) a));
			}
		}
		if (rdir > 0) {
			if (kk2 < kk) {	/* the rear end has problems */
				if (c * (ge->ix2 - ge->ix1) > 0) {
					ge->ix2 -= c;
					changed = 1;
				} if (d * (ge->iy3 - ge->iy2) > 0) {
					ge->iy2 += d;
					changed = 1;
				}
				/* recalculate the coefficients */
				a = ge->iy3 - ge->prev->iy3;
				b = ge->ix3 - ge->prev->ix3;
				kk = fabs(a == 0 ? (b == 0 ? 1. : 100000.) : ((double) b / (double) a));
				a = ge->iy3 - ge->iy2;
				b = ge->ix3 - ge->ix2;
				kk2 = fabs(a == 0 ? (b == 0 ? 1. : 100000.) : ((double) b / (double) a));
			}
		} else if (rdir < 0) {
			if (kk2 > kk) {	/* the rear end has problems */
				if (c * (ge->ix3 - ge->ix2) > 0) {
					ge->ix2 += c;
					changed = 1;
				} if (d * (ge->iy2 - ge->iy1) > 0) {
					ge->iy2 -= d;
					changed = 1;
				}
				/* recalculate the coefficients */
				a = ge->iy3 - ge->prev->iy3;
				b = ge->ix3 - ge->prev->ix3;
				kk = fabs(a == 0 ? (b == 0 ? 1. : 100000.) : ((double) b / (double) a));
				a = ge->iy3 - ge->iy2;
				b = ge->ix3 - ge->ix2;
				kk2 = fabs(a == 0 ? (b == 0 ? 1. : 100000.) : ((double) b / (double) a));
			}
		}
	}
	fixcvends(ge);
}

/* Get the directions of ends of curve for further usage */

/* expects that the previous element is also float */

static int
fgetcvdir(
	 GENTRY * ge
)
{
	double          a, b;
	double          k, k1, k2;
	int             dir = 0;

	if( !(ge->flags & GEF_FLOAT) ) {
		fprintf(stderr, "**! fgetcvdir(0x%x) on int entry, ABORT\n", ge);
		abort(); /* dump core */
	}

	a = fabs(ge->fy3 - ge->prev->fy3);
	b = fabs(ge->fx3 - ge->prev->fx3);
	k = a < FEPS ? (b < FEPS ? 1. : 100000.) : ( b / a);

	a = fabs(ge->fy1 - ge->prev->fy3);
	b = fabs(ge->fx1 - ge->prev->fx3);
	if(a < FEPS) {
		if(b < FEPS) {
			a = fabs(ge->fy2 - ge->prev->fy3);
			b = fabs(ge->fx2 - ge->prev->fx3);
			k1 = a < FEPS ? (b < FEPS ? k : 100000.) : ( b / a);
		} else
			k1 = FBIGVAL;
	} else
		k1 = b / a;

	a = fabs(ge->fy3 - ge->fy2);
	b = fabs(ge->fx3 - ge->fx2);
	if(a < FEPS) {
		if(b < FEPS) {
			a = fabs(ge->fy3 - ge->fy1);
			b = fabs(ge->fx3 - ge->fx1);
			k2 = a < FEPS ? (b < FEPS ? k : 100000.) : ( b / a);
		} else
			k2 = FBIGVAL;
	} else
		k2 = b / a;

	if(fabs(k1-k) < 0.0001)
		dir |= CVDIR_FEQUAL;
	else if (k1 < k)
		dir |= CVDIR_FUP;
	else
		dir |= CVDIR_FDOWN;

	if(fabs(k2-k) < 0.0001)
		dir |= CVDIR_REQUAL;
	else if (k2 > k)
		dir |= CVDIR_RUP;
	else
		dir |= CVDIR_RDOWN;

	return dir;
}


/* expects that the previous element is also int */

static int
igetcvdir(
	 GENTRY * ge
)
{
	int             a, b;
	double          k, k1, k2;
	int             dir = 0;

	if(ge->flags & GEF_FLOAT) {
		fprintf(stderr, "**! igetcvdir(0x%x) on floating entry, ABORT\n", ge);
		abort(); /* dump core */
	}

	a = ge->iy3 - ge->prev->iy3;
	b = ge->ix3 - ge->prev->ix3;
	k = (a == 0) ? (b == 0 ? 1. : 100000.) : fabs((double) b / (double) a);

	a = ge->iy1 - ge->prev->iy3;
	b = ge->ix1 - ge->prev->ix3;
	if(a == 0) {
		if(b == 0) {
			a = ge->iy2 - ge->prev->iy3;
			b = ge->ix2 - ge->prev->ix3;
			k1 = (a == 0) ? (b == 0 ? k : 100000.) : fabs((double) b / (double) a);
		} else
			k1 = FBIGVAL;
	} else
		k1 = fabs((double) b / (double) a);

	a = ge->iy3 - ge->iy2;
	b = ge->ix3 - ge->ix2;
	if(a == 0) {
		if(b == 0) {
			a = ge->iy3 - ge->iy1;
			b = ge->ix3 - ge->ix1;
			k2 = (a == 0) ? (b == 0 ? k : 100000.) : fabs((double) b / (double) a);
		} else
			k2 = FBIGVAL;
	} else
		k2 = fabs((double) b / (double) a);

	if(fabs(k1-k) < 0.0001)
		dir |= CVDIR_FEQUAL;
	else if (k1 < k)
		dir |= CVDIR_FUP;
	else
		dir |= CVDIR_FDOWN;

	if(fabs(k2-k) < 0.0001)
		dir |= CVDIR_REQUAL;
	else if (k2 > k)
		dir |= CVDIR_RUP;
	else
		dir |= CVDIR_RDOWN;

	return dir;
}

#if 0
/* a function just to test the work of fixcvdir() */
static void
testfixcvdir(
	     GLYPH * g
)
{
	GENTRY         *ge;
	int             dir;

	for (ge = g->entries; ge != 0; ge = ge->next) {
		if (ge->type == GE_CURVE) {
			dir = igetcvdir(ge);
			fixcvdir(ge, dir);
		}
	}
}
#endif

static int
iround(
	double val
)
{
	return (int) (val > 0 ? val + 0.5 : val - 0.5);
}
	
/* for debugging - dump the glyph
 * mark with a star the entries from start to end inclusive
 * (start == NULL means don't mark any, end == NULL means to the last)
 */

void
dumppaths(
	GLYPH *g,
	GENTRY *start,
	GENTRY *end
)
{
	GENTRY *ge;
	int i;
	char mark=' ';

	fprintf(stderr, "Glyph %s:\n", g->name);

	/* now do the conversion */
	for(ge = g->entries; ge != 0; ge = ge->next) {
		if(ge == start)
			mark = '*';
		fprintf(stderr, " %c %8x", mark, ge);
		switch(ge->type) {
		case GE_MOVE:
		case GE_LINE:
			if(ge->flags & GEF_FLOAT)
				fprintf(stderr," %c float (%g, %g)\n", ge->type, ge->fx3, ge->fy3);
			else
				fprintf(stderr," %c int (%d, %d)\n", ge->type, ge->ix3, ge->iy3);
			break;
		case GE_CURVE:
			if(ge->flags & GEF_FLOAT) {
				fprintf(stderr," C float ");
				for(i=0; i<3; i++)
					fprintf(stderr,"(%g, %g) ", ge->fxn[i], ge->fyn[i]);
				fprintf(stderr,"\n");
			} else {
				fprintf(stderr," C int ");
				for(i=0; i<3; i++)
					fprintf(stderr,"(%d, %d) ", ge->ixn[i], ge->iyn[i]);
				fprintf(stderr,"\n");
			}
			break;
		default:
			fprintf(stderr, " %c\n", ge->type);
			break;
		}
		if(ge == end)
			mark = ' ';
	}
}

/*
 * Routine that converts all entries in the path from float to int
 */

void
pathtoint(
	GLYPH *g
)
{
	GENTRY *ge;
	int x[3], y[3];
	int i;


	if(ISDBG(TOINT))
		fprintf(stderr, "TOINT: glyph %s\n", g->name);
	assertisfloat(g, "converting path to int\n");

	fdelsmall(g, 1.0); /* get rid of sub-pixel contours */
	assertpath(g->entries, __FILE__, __LINE__, g->name);

	/* 1st pass, collect the directions of the curves: have
	 * to do that in advance, while everyting is float
	 */
	for(ge = g->entries; ge != 0; ge = ge->next) {
		if( !(ge->flags & GEF_FLOAT) ) {
			fprintf(stderr, "**! glyphs %s has int entry, found in conversion to int\n",
				g->name);
			exit(1);
		}
		if(ge->type == GE_CURVE) {
			ge->dir = fgetcvdir(ge);
		}
	}

	/* now do the conversion */
	for(ge = g->entries; ge != 0; ge = ge->next) {
		switch(ge->type) {
		case GE_MOVE:
		case GE_LINE:
			if(ISDBG(TOINT))
				fprintf(stderr," %c float x=%g y=%g\n", ge->type, ge->fx3, ge->fy3);
			x[0] = iround(ge->fx3);
			y[0] = iround(ge->fy3);
			for(i=0; i<3; i++) { /* put some valid values everywhere, for convenience */
				ge->ixn[i] = x[0];
				ge->iyn[i] = y[0];
			}
			if(ISDBG(TOINT))
				fprintf(stderr,"   int   x=%d y=%d\n", ge->ix3, ge->iy3);
			break;
		case GE_CURVE:
			if(ISDBG(TOINT))
				fprintf(stderr," %c float ", ge->type);

			for(i=0; i<3; i++) {
				if(ISDBG(TOINT))
					fprintf(stderr,"(%g, %g) ", ge->fxn[i], ge->fyn[i]);
				x[i] = iround(ge->fxn[i]);
				y[i] = iround(ge->fyn[i]);
			}

			if(ISDBG(TOINT))
				fprintf(stderr,"\n   int   ");

			for(i=0; i<3; i++) {
				ge->ixn[i] = x[i];
				ge->iyn[i] = y[i];
				if(ISDBG(TOINT))
					fprintf(stderr,"(%d, %d) ", ge->ixn[i], ge->iyn[i]);
			}
			ge->flags &= ~GEF_FLOAT; /* for fixcvdir */
			fixcvdir(ge, ge->dir);

			if(ISDBG(TOINT)) {
				fprintf(stderr,"\n   fixed ");
				for(i=0; i<3; i++)
					fprintf(stderr,"(%d, %d) ", ge->ixn[i], ge->iyn[i]);
				fprintf(stderr,"\n");
			}

			break;
		}
		ge->flags &= ~GEF_FLOAT;
	}
	g->flags &= ~GF_FLOAT;
}


/* check whether we can fix up the curve to change its size by (dx,dy) */
/* 0 means NO, 1 means YES */

/* for float: if scaling would be under 10% */

int
fcheckcv(
	GENTRY * ge,
	double dx,
	double dy
)
{
	if( !(ge->flags & GEF_FLOAT) ) {
		fprintf(stderr, "**! fcheckcv(0x%x) on int entry, ABORT\n", ge);
		abort(); /* dump core */
	}

	if (ge->type != GE_CURVE)
		return 0;

	if( fabs(ge->fx3 - ge->prev->fx3) < fabs(dx) * 10 )
		return 0;

	if( fabs(ge->fy3 - ge->prev->fy3) < fabs(dy) * 10 )
		return 0;

	return 1;
}

/* for int: if won't create new zigzags at the ends */

int
icheckcv(
	GENTRY * ge,
	int dx,
	int dy
)
{
	int             xdep, ydep;

	if(ge->flags & GEF_FLOAT) {
		fprintf(stderr, "**! icheckcv(0x%x) on floating entry, ABORT\n", ge);
		abort(); /* dump core */
	}

	if (ge->type != GE_CURVE)
		return 0;

	xdep = ge->ix3 - ge->prev->ix3;
	ydep = ge->iy3 - ge->prev->iy3;

	if (ge->type == GE_CURVE
	    && (xdep * (xdep + dx)) > 0
	    && (ydep * (ydep + dy)) > 0) {
		return 1;
	} else
		return 0;
}

/* float connect the ends of open contours */

void
fclosepaths(
	   GLYPH * g
)
{
	GENTRY         *ge, *fge, *xge, *nge;
	int             i;

	assertisfloat(g, "fclosepaths float\n");

	for (xge = g->entries; xge != 0; xge = xge->next) {
		if( xge->type != GE_PATH )
			continue;

		ge = xge->prev;
		if(ge == 0 || ge->type != GE_LINE && ge->type!= GE_CURVE) {
			fprintf(stderr, "**! Glyph %s got empty path\n",
				g->name);
			exit(1);
		}

		fge = ge->frwd;
		if (fge->prev == 0 || fge->prev->type != GE_MOVE) {
			fprintf(stderr, "**! Glyph %s got strange beginning of path\n",
				g->name);
			exit(1);
		}
		fge = fge->prev;
		if (fge->fx3 != ge->fx3 || fge->fy3 != ge->fy3) {
			/* we have to fix this open path */

			WARNING_4 fprintf(stderr, "Glyph %s got path open by dx=%g dy=%g\n",
			g->name, fge->fx3 - ge->fx3, fge->fy3 - ge->fy3);


			/* add a new line */
			nge = newgentry(GEF_FLOAT);
			(*nge) = (*ge);
			nge->fx3 = fge->fx3;
			nge->fy3 = fge->fy3;
			nge->type = GE_LINE;

			addgeafter(ge, nge);

			if (fabs(ge->fx3 - fge->fx3) <= 2 && fabs(ge->fy3 - fge->fy3) <= 2) {
				/*
				 * small change, try to get rid of the new entry
				 */

				double df[2];

				for(i=0; i<2; i++) {
					df[i] = ge->fpoints[i][2] - fge->fpoints[i][2];
					df[i] = fclosegap(nge, nge, i, df[i], NULL);
				}

				if(df[0] == 0. && df[1] == 0.) {
					/* closed gap successfully, remove the added entry */
					freethisge(nge);
				}
			}
		}
	}
}

void
smoothjoints(
	     GLYPH * g
)
{
	GENTRY         *ge, *ne;
	int             dx1, dy1, dx2, dy2, k;
	int             dir;

	return; /* this stuff seems to create problems */

	assertisint(g, "smoothjoints int");

	if (g->entries == 0)	/* nothing to do */
		return;

	for (ge = g->entries->next; ge != 0; ge = ge->next) {
		ne = ge->frwd;

		/*
		 * although there should be no one-line path * and any path
		 * must end with CLOSEPATH, * nobody can say for sure
		 */

		if (ge == ne || ne == 0)
			continue;

		/* now handle various joints */

		if (ge->type == GE_LINE && ne->type == GE_LINE) {
			dx1 = ge->ix3 - ge->prev->ix3;
			dy1 = ge->iy3 - ge->prev->iy3;
			dx2 = ne->ix3 - ge->ix3;
			dy2 = ne->iy3 - ge->iy3;

			/* check whether they have the same direction */
			/* and the same slope */
			/* then we can join them into one line */

			if (dx1 * dx2 >= 0 && dy1 * dy2 >= 0 && dx1 * dy2 == dy1 * dx2) {
				/* extend the previous line */
				ge->ix3 = ne->ix3;
				ge->iy3 = ne->iy3;

				/* and get rid of the next line */
				freethisge(ne);
			}
		} else if (ge->type == GE_LINE && ne->type == GE_CURVE) {
			fixcvends(ne);

			dx1 = ge->ix3 - ge->prev->ix3;
			dy1 = ge->iy3 - ge->prev->iy3;
			dx2 = ne->ix1 - ge->ix3;
			dy2 = ne->iy1 - ge->iy3;

			/* if the line is nearly horizontal and we can fix it */
			if (dx1 != 0 && 5 * abs(dy1) / abs(dx1) == 0
			    && icheckcv(ne, 0, -dy1)
			    && abs(dy1) <= 4) {
				dir = igetcvdir(ne);
				ge->iy3 -= dy1;
				ne->iy1 -= dy1;
				fixcvdir(ne, dir);
				if (ge->next != ne)
					ne->prev->iy3 -= dy1;
				dy1 = 0;
			} else if (dy1 != 0 && 5 * abs(dx1) / abs(dy1) == 0
				   && icheckcv(ne, -dx1, 0)
				   && abs(dx1) <= 4) {
				/* the same but vertical */
				dir = igetcvdir(ne);
				ge->ix3 -= dx1;
				ne->ix1 -= dx1;
				fixcvdir(ne, dir);
				if (ge->next != ne)
					ne->prev->ix3 -= dx1;
				dx1 = 0;
			}
			/*
			 * if line is horizontal and curve begins nearly
			 * horizontally
			 */
			if (dy1 == 0 && dx2 != 0 && 5 * abs(dy2) / abs(dx2) == 0) {
				dir = igetcvdir(ne);
				ne->iy1 -= dy2;
				fixcvdir(ne, dir);
				dy2 = 0;
			} else if (dx1 == 0 && dy2 != 0 && 5 * abs(dx2) / abs(dy2) == 0) {
				/* the same but vertical */
				dir = igetcvdir(ne);
				ne->ix1 -= dx2;
				fixcvdir(ne, dir);
				dx2 = 0;
			}
		} else if (ge->type == GE_CURVE && ne->type == GE_LINE) {
			fixcvends(ge);

			dx1 = ge->ix3 - ge->ix2;
			dy1 = ge->iy3 - ge->iy2;
			dx2 = ne->ix3 - ge->ix3;
			dy2 = ne->iy3 - ge->iy3;

			/* if the line is nearly horizontal and we can fix it */
			if (dx2 != 0 && 5 * abs(dy2) / abs(dx2) == 0
			    && icheckcv(ge, 0, dy2)
			    && abs(dy2) <= 4) {
				dir = igetcvdir(ge);
				ge->iy3 += dy2;
				ge->iy2 += dy2;
				fixcvdir(ge, dir);
				if (ge->next != ne)
					ne->prev->iy3 += dy2;
				dy2 = 0;
			} else if (dy2 != 0 && 5 * abs(dx2) / abs(dy2) == 0
				   && icheckcv(ge, dx2, 0)
				   && abs(dx2) <= 4) {
				/* the same but vertical */
				dir = igetcvdir(ge);
				ge->ix3 += dx2;
				ge->ix2 += dx2;
				fixcvdir(ge, dir);
				if (ge->next != ne)
					ne->prev->ix3 += dx2;
				dx2 = 0;
			}
			/*
			 * if line is horizontal and curve ends nearly
			 * horizontally
			 */
			if (dy2 == 0 && dx1 != 0 && 5 * abs(dy1) / abs(dx1) == 0) {
				dir = igetcvdir(ge);
				ge->iy2 += dy1;
				fixcvdir(ge, dir);
				dy1 = 0;
			} else if (dx2 == 0 && dy1 != 0 && 5 * abs(dx1) / abs(dy1) == 0) {
				/* the same but vertical */
				dir = igetcvdir(ge);
				ge->ix2 += dx1;
				fixcvdir(ge, dir);
				dx1 = 0;
			}
		} else if (ge->type == GE_CURVE && ne->type == GE_CURVE) {
			fixcvends(ge);
			fixcvends(ne);

			dx1 = ge->ix3 - ge->ix2;
			dy1 = ge->iy3 - ge->iy2;
			dx2 = ne->ix1 - ge->ix3;
			dy2 = ne->iy1 - ge->iy3;

			/*
			 * check if we have a rather smooth joint at extremal
			 * point
			 */
			/* left or right extremal point */
			if (abs(dx1) <= 4 && abs(dx2) <= 4
			    && dy1 != 0 && 5 * abs(dx1) / abs(dy1) == 0
			    && dy2 != 0 && 5 * abs(dx2) / abs(dy2) == 0
			    && (ge->iy3 < ge->prev->iy3 && ne->iy3 < ge->iy3
				|| ge->iy3 > ge->prev->iy3 && ne->iy3 > ge->iy3)
			  && (ge->ix3 - ge->prev->ix3) * (ne->ix3 - ge->ix3) < 0
				) {
				dir = igetcvdir(ge);
				ge->ix2 += dx1;
				dx1 = 0;
				fixcvdir(ge, dir);
				dir = igetcvdir(ne);
				ne->ix1 -= dx2;
				dx2 = 0;
				fixcvdir(ne, dir);
			}
			/* top or down extremal point */
			else if (abs(dy1) <= 4 && abs(dy2) <= 4
				 && dx1 != 0 && 5 * abs(dy1) / abs(dx1) == 0
				 && dx2 != 0 && 5 * abs(dy2) / abs(dx2) == 0
				 && (ge->ix3 < ge->prev->ix3 && ne->ix3 < ge->ix3
				|| ge->ix3 > ge->prev->ix3 && ne->ix3 > ge->ix3)
				 && (ge->iy3 - ge->prev->iy3) * (ne->iy3 - ge->iy3) < 0
				) {
				dir = igetcvdir(ge);
				ge->iy2 += dy1;
				dy1 = 0;
				fixcvdir(ge, dir);
				dir = igetcvdir(ne);
				ne->iy1 -= dy2;
				dy2 = 0;
				fixcvdir(ne, dir);
			}
			/* or may be we just have a smooth junction */
			else if (dx1 * dx2 >= 0 && dy1 * dy2 >= 0
				 && 10 * abs(k = abs(dx1 * dy2) - abs(dy1 * dx2)) < (abs(dx1 * dy2) + abs(dy1 * dx2))) {
				int             tries[6][4];
				int             results[6];
				int             i, b;

				/* build array of changes we are going to try */
				/* uninitalized entries are 0 */
				if (k > 0) {
					static int      t1[6][4] = {
						{0, 0, 0, 0},
						{-1, 0, 1, 0},
						{-1, 0, 0, 1},
						{0, -1, 1, 0},
						{0, -1, 0, 1},
					{-1, -1, 1, 1}};
					memcpy(tries, t1, sizeof tries);
				} else {
					static int      t1[6][4] = {
						{0, 0, 0, 0},
						{1, 0, -1, 0},
						{1, 0, 0, -1},
						{0, 1, -1, 0},
						{0, 1, 0, -1},
					{1, 1, -1, -1}};
					memcpy(tries, t1, sizeof tries);
				}

				/* now try the changes */
				results[0] = abs(k);
				for (i = 1; i < 6; i++) {
					results[i] = abs((abs(dx1) + tries[i][0]) * (abs(dy2) + tries[i][1]) -
							 (abs(dy1) + tries[i][2]) * (abs(dx2) + tries[i][3]));
				}

				/* and find the best try */
				k = abs(k);
				b = 0;
				for (i = 1; i < 6; i++)
					if (results[i] < k) {
						k = results[i];
						b = i;
					}
				/* and finally apply it */
				if (dx1 < 0)
					tries[b][0] = -tries[b][0];
				if (dy2 < 0)
					tries[b][1] = -tries[b][1];
				if (dy1 < 0)
					tries[b][2] = -tries[b][2];
				if (dx2 < 0)
					tries[b][3] = -tries[b][3];

				dir = igetcvdir(ge);
				ge->ix2 -= tries[b][0];
				ge->iy2 -= tries[b][2];
				fixcvdir(ge, dir);
				dir = igetcvdir(ne);
				ne->ix1 += tries[b][3];
				ne->iy1 += tries[b][1];
				fixcvdir(ne, dir);
			}
		}
	}
}

/* debugging: print out stems of a glyph */
static void
debugstems(
	   char *name,
	   STEM * hstems,
	   int nhs,
	   STEM * vstems,
	   int nvs
)
{
	int             i;

	fprintf(pfa_file, "%% %s\n", name);
	fprintf(pfa_file, "%% %d horizontal stems:\n", nhs);
	for (i = 0; i < nhs; i++)
		fprintf(pfa_file, "%% %3d    %d (%d...%d) %c %c%c%c%c\n", i, hstems[i].value,
			hstems[i].from, hstems[i].to,
			((hstems[i].flags & ST_UP) ? 'U' : 'D'),
			((hstems[i].flags & ST_END) ? 'E' : '-'),
			((hstems[i].flags & ST_FLAT) ? 'F' : '-'),
			((hstems[i].flags & ST_ZONE) ? 'Z' : ' '),
			((hstems[i].flags & ST_TOPZONE) ? 'T' : ' '));
	fprintf(pfa_file, "%% %d vertical stems:\n", nvs);
	for (i = 0; i < nvs; i++)
		fprintf(pfa_file, "%% %3d    %d (%d...%d) %c %c%c\n", i, vstems[i].value,
			vstems[i].from, vstems[i].to,
			((vstems[i].flags & ST_UP) ? 'U' : 'D'),
			((vstems[i].flags & ST_END) ? 'E' : '-'),
			((vstems[i].flags & ST_FLAT) ? 'F' : '-'));
}

/* add pseudo-stems for the limits of the Blue zones to the stem array */
static int
addbluestems(
	STEM *s,
	int n
)
{
	int i;

	for(i=0; i<nblues && i<2; i+=2) { /* baseline */
		s[n].value=bluevalues[i];
		s[n].flags=ST_UP|ST_ZONE;
		/* don't overlap with anything */
		s[n].origin=s[n].from=s[n].to= -10000+i;
		n++;
		s[n].value=bluevalues[i+1];
		s[n].flags=ST_ZONE;
		/* don't overlap with anything */
		s[n].origin=s[n].from=s[n].to= -10000+i+1;
		n++;
	}
	for(i=2; i<nblues; i+=2) { /* top zones */
		s[n].value=bluevalues[i];
		s[n].flags=ST_UP|ST_ZONE|ST_TOPZONE;
		/* don't overlap with anything */
		s[n].origin=s[n].from=s[n].to= -10000+i;
		n++;
		s[n].value=bluevalues[i+1];
		s[n].flags=ST_ZONE|ST_TOPZONE;
		/* don't overlap with anything */
		s[n].origin=s[n].from=s[n].to= -10000+i+1;
		n++;
	}
	for(i=0; i<notherb; i+=2) { /* bottom zones */
		s[n].value=otherblues[i];
		s[n].flags=ST_UP|ST_ZONE;
		/* don't overlap with anything */
		s[n].origin=s[n].from=s[n].to= -10000+i+nblues;
		n++;
		s[n].value=otherblues[i+1];
		s[n].flags=ST_ZONE;
		/* don't overlap with anything */
		s[n].origin=s[n].from=s[n].to= -10000+i+1+nblues;
		n++;
	}
	return n;
}

/* sort stems in array */
static void
sortstems(
	  STEM * s,
	  int n
)
{
	int             i, j;
	STEM            x;


	/* a simple sorting */
	/* hm, the ordering criteria are not quite simple :-) 
	 * if the values are tied
	 * ST_UP always goes under not ST_UP
	 * ST_ZONE goes on the most outer side
	 * ST_END goes towards inner side after ST_ZONE
	 * ST_FLAT goes on the inner side
	 */

	for (i = 0; i < n; i++)
		for (j = i + 1; j < n; j++) {
			if(s[i].value < s[j].value)
				continue;
			if(s[i].value == s[j].value) {
				if( (s[i].flags & ST_UP) < (s[j].flags & ST_UP) )
					continue;
				if( (s[i].flags & ST_UP) == (s[j].flags & ST_UP) ) {
					if( s[i].flags & ST_UP ) {
						if(
						(s[i].flags & (ST_ZONE|ST_FLAT|ST_END) ^ ST_FLAT)
							>
						(s[j].flags & (ST_ZONE|ST_FLAT|ST_END) ^ ST_FLAT)
						)
							continue;
					} else {
						if(
						(s[i].flags & (ST_ZONE|ST_FLAT|ST_END) ^ ST_FLAT)
							<
						(s[j].flags & (ST_ZONE|ST_FLAT|ST_END) ^ ST_FLAT)
						)
							continue;
					}
				}
			}
			x = s[j];
			s[j] = s[i];
			s[i] = x;
		}
}

/* check whether two stem borders overlap */

static int
stemoverlap(
	    STEM * s1,
	    STEM * s2
)
{
	int             result;

	if (s1->from <= s2->from && s1->to >= s2->from
	    || s2->from <= s1->from && s2->to >= s1->from)
		result = 1;
	else
		result = 0;

	if (ISDBG(STEMOVERLAP))
		fprintf(pfa_file, "%% overlap %d(%d..%d)x%d(%d..%d)=%d\n",
			s1->value, s1->from, s1->to, s2->value, s2->from, s2->to, result);
	return result;
}

/* 
 * check if the stem [border] is in an appropriate blue zone
 * (currently not used)
 */

static int
steminblue(
	STEM *s
)
{
	int i, val;

	val=s->value;
	if(s->flags & ST_UP) {
		/* painted size up, look at lower zones */
		if(nblues>=2 && val>=bluevalues[0] && val<=bluevalues[1] )
			return 1;
		for(i=0; i<notherb; i++) {
			if( val>=otherblues[i] && val<=otherblues[i+1] )
				return 1;
		}
	} else {
		/* painted side down, look at upper zones */
		for(i=2; i<nblues; i++) {
			if( val>=bluevalues[i] && val<=bluevalues[i+1] )
				return 1;
		}
	}

	return 0;
}

/* mark the outermost stem [borders] in the blue zones */

static void
markbluestems(
	STEM *s,
	int nold
)
{
	int i, j, a, b, c;
	/*
	 * traverse the list of Blue Values, mark the lowest upper
	 * stem in each bottom zone and the topmost lower stem in
	 * each top zone with ST_BLUE
	 */

	/* top zones */
	for(i=2; i<nblues; i+=2) {
		a=bluevalues[i]; b=bluevalues[i+1];
		if(ISDBG(BLUESTEMS))
			fprintf(pfa_file, "%% looking at blue zone %d...%d\n", a, b);
		for(j=nold-1; j>=0; j--) {
			if( s[j].flags & (ST_ZONE|ST_UP|ST_END) )
				continue;
			c=s[j].value;
			if(c<a) /* too low */
				break;
			if(c<=b) { /* found the topmost stem border */
				/* mark all the stems with the same value */
				if(ISDBG(BLUESTEMS))
					fprintf(pfa_file, "%% found D BLUE at %d\n", s[j].value);
				/* include ST_END values */
				while( s[j+1].value==c && (s[j+1].flags & ST_ZONE)==0 )
					j++;
				s[j].flags |= ST_BLUE;
				for(j--; j>=0 && s[j].value==c 
						&& (s[j].flags & (ST_UP|ST_ZONE))==0 ; j--)
					s[j].flags |= ST_BLUE;
				break;
			}
		}
	}
	/* baseline */
	if(nblues>=2) {
		a=bluevalues[0]; b=bluevalues[1];
		for(j=0; j<nold; j++) {
			if( (s[j].flags & (ST_ZONE|ST_UP|ST_END))!=ST_UP )
				continue;
			c=s[j].value;
			if(c>b) /* too high */
				break;
			if(c>=a) { /* found the lowest stem border */
				/* mark all the stems with the same value */
				if(ISDBG(BLUESTEMS))
					fprintf(pfa_file, "%% found U BLUE at %d\n", s[j].value);
				/* include ST_END values */
				while( s[j-1].value==c && (s[j-1].flags & ST_ZONE)==0 )
					j--;
				s[j].flags |= ST_BLUE;
				for(j++; j<nold && s[j].value==c
						&& (s[j].flags & (ST_UP|ST_ZONE))==ST_UP ; j++)
					s[j].flags |= ST_BLUE;
				break;
			}
		}
	}
	/* bottom zones: the logic is the same as for baseline */
	for(i=0; i<notherb; i+=2) {
		a=otherblues[i]; b=otherblues[i+1];
		for(j=0; j<nold; j++) {
			if( (s[j].flags & (ST_UP|ST_ZONE|ST_END))!=ST_UP )
				continue;
			c=s[j].value;
			if(c>b) /* too high */
				break;
			if(c>=a) { /* found the lowest stem border */
				/* mark all the stems with the same value */
				if(ISDBG(BLUESTEMS))
					fprintf(pfa_file, "%% found U BLUE at %d\n", s[j].value);
				/* include ST_END values */
				while( s[j-1].value==c && (s[j-1].flags & ST_ZONE)==0 )
					j--;
				s[j].flags |= ST_BLUE;
				for(j++; j<nold && s[j].value==c
						&& (s[j].flags & (ST_UP|ST_ZONE))==ST_UP ; j++)
					s[j].flags |= ST_BLUE;
				break;
			}
		}
	}
}

/* Eliminate invalid stems, join equivalent lines and remove nested stems
 * to build the main (non-substituted) set of stems.
 * XXX add consideration of the italic angle
 */
static int
joinmainstems(
	  STEM * s,
	  int nold,
	  int useblues /* do we use the blue values ? */
)
{
#define MAX_STACK	1000
	STEM            stack[MAX_STACK];
	int             nstack = 0;
	int             sbottom = 0;
	int             nnew;
	int             i, j, k;
	int             a, b, c, w1, w2, w3;
	int             fw, fd;
	/*
	 * priority of the last found stem: 
	 * 0 - nothing found yet 
	 * 1 - has ST_END in it (one or more) 
	 * 2 - has no ST_END and no ST_FLAT, can override only one stem 
	 *     with priority 1 
	 * 3 - has no ST_END and at least one ST_FLAT, can override one 
	 *     stem with priority 2 or any number of stems with priority 1
	 * 4 (handled separately) - has ST_BLUE, can override anything
	 */
	int             readystem = 0;
	int             pri;
	int             nlps = 0;	/* number of non-committed
					 * lowest-priority stems */


	for (i = 0, nnew = 0; i < nold; i++) {
		if (s[i].flags & (ST_UP|ST_ZONE)) {
			if(s[i].flags & ST_BLUE) {
				/* we just HAVE to use this value */
				if (readystem)
					nnew += 2;
				readystem=0;

				/* remember the list of Blue zone stems with the same value */
				for(a=i, i++; i<nold && s[a].value==s[i].value
					&& (s[i].flags & ST_BLUE); i++)
					{}
				b=i; /* our range is a <= i < b */
				c= -1; /* index of our best guess up to now */
				pri=0;
				/* try to find a match, don't cross blue zones */
				for(; i<nold && (s[i].flags & ST_BLUE)==0; i++) {
					if(s[i].flags & ST_UP) {
						if(s[i].flags & ST_TOPZONE)
							break;
						else
							continue;
					}
					for(j=a; j<b; j++) {
						if(!stemoverlap(&s[j], &s[i]) )
							continue;
						/* consider priorities */
						if( ( (s[j].flags|s[i].flags) & (ST_FLAT|ST_END) )==ST_FLAT ) {
							c=i;
							goto bluematch;
						}
						if( ((s[j].flags|s[i].flags) & ST_END)==0 )  {
							if(pri < 2) {
								c=i; pri=2;
							}
						} else {
							if(pri == 0) {
								c=i; pri=1;
							}
						}
					}
				}
			bluematch:
				/* clean up the stack */
				nstack=sbottom=0;
				readystem=0;
				/* add this stem */
				s[nnew++]=s[a];
				if(c<0) { /* make one-dot-wide stem */
					if(nnew>=b) { /* have no free space */
						for(j=nold; j>=b; j--) /* make free space */
							s[j]=s[j-1];
						b++;
						nold++;
					}
					s[nnew]=s[a];
					s[nnew].flags &= ~(ST_UP|ST_BLUE);
					nnew++;
					i=b-1;
				} else {
					s[nnew++]=s[c];
					i=c; /* skip up to this point */
				}
				if (ISDBG(MAINSTEMS))
					fprintf(pfa_file, "%% +stem %d...%d U BLUE\n",
						s[nnew-2].value, s[nnew-1].value);
			} else {
				if (nstack >= MAX_STACK) {
					WARNING_1 fprintf(stderr, "Warning: **** converter's stem stack overflow ****\n");
					nstack = 0;
				}
				stack[nstack++] = s[i];
			}
		} else if(s[i].flags & ST_BLUE) {
			/* again, we just HAVE to use this value */
			if (readystem)
				nnew += 2;
			readystem=0;

			/* remember the list of Blue zone stems with the same value */
			for(a=i, i++; i<nold && s[a].value==s[i].value
				&& (s[i].flags & ST_BLUE); i++)
				{}
			b=i; /* our range is a <= i < b */
			c= -1; /* index of our best guess up to now */
			pri=0;
			/* try to find a match */
			for (i = nstack - 1; i >= 0; i--) {
				if( (stack[i].flags & ST_UP)==0 ) {
					if( (stack[i].flags & (ST_ZONE|ST_TOPZONE))==ST_ZONE )
						break;
					else
						continue;
				}
				for(j=a; j<b; j++) {
					if(!stemoverlap(&s[j], &stack[i]) )
						continue;
					/* consider priorities */
					if( ( (s[j].flags|stack[i].flags) & (ST_FLAT|ST_END) )==ST_FLAT ) {
						c=i;
						goto bluedownmatch;
					}
					if( ((s[j].flags|stack[i].flags) & ST_END)==0 )  {
						if(pri < 2) {
							c=i; pri=2;
						}
					} else {
						if(pri == 0) {
							c=i; pri=1;
						}
					}
				}
			}
		bluedownmatch:
			/* if found no match make a one-dot-wide stem */
			if(c<0) {
				c=0;
				stack[0]=s[b-1];
				stack[0].flags |= ST_UP;
				stack[0].flags &= ~ST_BLUE;
			}
			/* remove all the stems conflicting with this one */
			readystem=0;
			for(j=nnew-2; j>=0; j-=2) {
				if (ISDBG(MAINSTEMS))
					fprintf(pfa_file, "%% ?stem %d...%d -- %d\n",
						s[j].value, s[j+1].value, stack[c].value);
				if(s[j+1].value < stack[c].value) /* no conflict */
					break;
				if(s[j].flags & ST_BLUE) {
					/* oops, we don't want to spoil other blue zones */
					stack[c].value=s[j+1].value+1;
					break;
				}
				if( (s[j].flags|s[j+1].flags) & ST_END ) {
					if (ISDBG(MAINSTEMS))
						fprintf(pfa_file, "%% -stem %d...%d p=1\n",
							s[j].value, s[j+1].value);
					continue; /* pri==1, silently discard it */
				}
				/* we want to discard no nore than 2 stems of pri>=2 */
				if( ++readystem > 2 ) {
					/* change our stem to not conflict */
					stack[c].value=s[j+1].value+1;
					break;
				} else {
					if (ISDBG(MAINSTEMS))
						fprintf(pfa_file, "%% -stem %d...%d p>=2\n",
							s[j].value, s[j+1].value);
					continue;
				}
			}
			nnew=j+2;
			/* add this stem */
			if(nnew>=b-1) { /* have no free space */
				for(j=nold; j>=b-1; j--) /* make free space */
					s[j]=s[j-1];
				b++;
				nold++;
			}
			s[nnew++]=stack[c];
			s[nnew++]=s[b-1];
			/* clean up the stack */
			nstack=sbottom=0;
			readystem=0;
			/* set the next position to search */
			i=b-1;
			if (ISDBG(MAINSTEMS))
				fprintf(pfa_file, "%% +stem %d...%d D BLUE\n",
					s[nnew-2].value, s[nnew-1].value);
		} else if (nstack > 0) {

			/*
			 * check whether our stem overlaps with anything in
			 * stack
			 */
			for (j = nstack - 1; j >= sbottom; j--) {
				if (s[i].value <= stack[j].value)
					break;
				if (stack[j].flags & ST_ZONE)
					continue;

				if ((s[i].flags & ST_END)
				    || (stack[j].flags & ST_END))
					pri = 1;
				else if ((s[i].flags & ST_FLAT)
					 || (stack[j].flags & ST_FLAT))
					pri = 3;
				else
					pri = 2;

				if (pri < readystem && s[nnew + 1].value >= stack[j].value
				    || !stemoverlap(&stack[j], &s[i]))
					continue;

				if (readystem > 1 && s[nnew + 1].value < stack[j].value) {
					nnew += 2;
					readystem = 0;
					nlps = 0;
				}
				/*
				 * width of the previous stem (if it's
				 * present)
				 */
				w1 = s[nnew + 1].value - s[nnew].value;

				/* width of this stem */
				w2 = s[i].value - stack[j].value;

				if (readystem == 0) {
					/* nothing yet, just add a new stem */
					s[nnew] = stack[j];
					s[nnew + 1] = s[i];
					readystem = pri;
					if (pri == 1)
						nlps = 1;
					else if (pri == 2)
						sbottom = j;
					else {
						sbottom = j + 1;
						while (sbottom < nstack
						       && stack[sbottom].value <= stack[j].value)
							sbottom++;
					}
					if (ISDBG(MAINSTEMS))
						fprintf(pfa_file, "%% +stem %d...%d p=%d n=%d\n",
							stack[j].value, s[i].value, pri, nlps);
				} else if (pri == 1) {
					if (stack[j].value > s[nnew + 1].value) {
						/*
						 * doesn't overlap with the
						 * previous one
						 */
						nnew += 2;
						nlps++;
						s[nnew] = stack[j];
						s[nnew + 1] = s[i];
						if (ISDBG(MAINSTEMS))
							fprintf(pfa_file, "%% +stem %d...%d p=%d n=%d\n",
								stack[j].value, s[i].value, pri, nlps);
					} else if (w2 < w1) {
						/* is narrower */
						s[nnew] = stack[j];
						s[nnew + 1] = s[i];
						if (ISDBG(MAINSTEMS))
							fprintf(pfa_file, "%% /stem %d...%d p=%d n=%d %d->%d\n",
								stack[j].value, s[i].value, pri, nlps, w1, w2);
					}
				} else if (pri == 2) {
					if (readystem == 2) {
						/* choose the narrower stem */
						if (w1 > w2) {
							s[nnew] = stack[j];
							s[nnew + 1] = s[i];
							sbottom = j;
							if (ISDBG(MAINSTEMS))
								fprintf(pfa_file, "%% /stem %d...%d p=%d n=%d\n",
									stack[j].value, s[i].value, pri, nlps);
						}
						/* else readystem==1 */
					} else if (stack[j].value > s[nnew + 1].value) {
						/*
						 * value doesn't overlap with
						 * the previous one
						 */
						nnew += 2;
						nlps = 0;
						s[nnew] = stack[j];
						s[nnew + 1] = s[i];
						sbottom = j;
						readystem = pri;
						if (ISDBG(MAINSTEMS))
							fprintf(pfa_file, "%% +stem %d...%d p=%d n=%d\n",
								stack[j].value, s[i].value, pri, nlps);
					} else if (nlps == 1
						   || stack[j].value > s[nnew - 1].value) {
						/*
						 * we can replace the top
						 * stem
						 */
						nlps = 0;
						s[nnew] = stack[j];
						s[nnew + 1] = s[i];
						readystem = pri;
						sbottom = j;
						if (ISDBG(MAINSTEMS))
							fprintf(pfa_file, "%% /stem %d...%d p=%d n=%d\n",
								stack[j].value, s[i].value, pri, nlps);
					}
				} else if (readystem == 3) {	/* that means also
								 * pri==3 */
					/* choose the narrower stem */
					if (w1 > w2) {
						s[nnew] = stack[j];
						s[nnew + 1] = s[i];
						sbottom = j + 1;
						while (sbottom < nstack
						       && stack[sbottom].value <= stack[j].value)
							sbottom++;
						if (ISDBG(MAINSTEMS))
							fprintf(pfa_file, "%% /stem %d...%d p=%d n=%d\n",
								stack[j].value, s[i].value, pri, nlps);
					}
				} else if (pri == 3) {
					/*
					 * we can replace as many stems as
					 * neccessary
					 */
					nnew += 2;
					while (nnew > 0 && s[nnew - 1].value >= stack[j].value) {
						nnew -= 2;
						if (ISDBG(MAINSTEMS))
							fprintf(pfa_file, "%% -stem %d..%d\n",
								s[nnew].value, s[nnew + 1].value);
					}
					nlps = 0;
					s[nnew] = stack[j];
					s[nnew + 1] = s[i];
					readystem = pri;
					sbottom = j + 1;
					while (sbottom < nstack
					       && stack[sbottom].value <= stack[j].value)
						sbottom++;
					if (ISDBG(MAINSTEMS))
						fprintf(pfa_file, "%% +stem %d...%d p=%d n=%d\n",
							stack[j].value, s[i].value, pri, nlps);
				}
			}
		}
	}
	if (readystem)
		nnew += 2;

	/* change the 1-pixel-wide stems to 20-pixel-wide stems if possible 
	 * the constant 20 is recommended in the Type1 manual 
	 */
	if(useblues) {
		for(i=0; i<nnew; i+=2) {
			if(s[i].value != s[i+1].value)
				continue;
			if( ((s[i].flags ^ s[i+1].flags) & ST_BLUE)==0 )
				continue;
			if( s[i].flags & ST_BLUE ) {
				if(nnew>i+2 && s[i+2].value<s[i].value+22)
					s[i+1].value=s[i+2].value-2; /* compensate for fuzziness */
				else
					s[i+1].value+=20;
			} else {
				if(i>0 && s[i-1].value>s[i].value-22)
					s[i].value=s[i-1].value+2; /* compensate for fuzziness */
				else
					s[i].value-=20;
			}
		}
	}
	/* make sure that no stem it stretched between
	 * a top zone and a bottom zone
	 */
	if(useblues) {
		for(i=0; i<nnew; i+=2) {
			a=10000; /* lowest border of top zone crosing the stem */
			b= -10000; /* highest border of bottom zone crossing the stem */

			for(j=2; j<nblues; j++) {
				c=bluevalues[j];
				if( c>=s[i].value && c<=s[i+1].value && c<a )
					a=c;
			}
			if(nblues>=2) {
				c=bluevalues[1];
				if( c>=s[i].value && c<=s[i+1].value && c>b )
					b=c;
			}
			for(j=1; j<notherb; j++) {
				c=otherblues[j];
				if( c>=s[i].value && c<=s[i+1].value && c>b )
					b=c;
			}
			if( a!=10000 && b!= -10000 ) { /* it is stretched */
				/* split the stem into 2 ghost stems */
				for(j=nnew+1; j>i+1; j--) /* make free space */
					s[j]=s[j-2];
				nnew+=2;

				if(s[i].value+22 >= a)
					s[i+1].value=a-2; /* leave space for fuzziness */
				else
					s[i+1].value=s[i].value+20;

				if(s[i+3].value-22 <= b)
					s[i+2].value=b+2; /* leave space for fuzziness */
				else
					s[i+2].value=s[i+3].value-20;

				i+=2;
			}
		}
	}
	/* look for triple stems */
	for (i = 0; i < nnew; i += 2) {
		if (nnew - i >= 6) {
			a = s[i].value + s[i + 1].value;
			b = s[i + 2].value + s[i + 3].value;
			c = s[i + 4].value + s[i + 5].value;

			w1 = s[i + 1].value - s[i].value;
			w2 = s[i + 3].value - s[i + 2].value;
			w3 = s[i + 5].value - s[i + 4].value;

			fw = w3 - w1;	/* fuzz in width */
			fd = ((c - b) - (b - a));	/* fuzz in distance
							 * (doubled) */

			/* we are able to handle some fuzz */
			/*
			 * it doesn't hurt if the declared stem is a bit
			 * narrower than actual unless it's an edge in
			 * a blue zone
			 */
			if (abs(abs(fd) - abs(fw)) * 5 < w2
			    && abs(fw) * 20 < (w1 + w3)) {	/* width dirrerence <10% */

				if(useblues) { /* check that we don't disturb any blue stems */
					j=c; k=a;
					if (fw > 0) {
						if (fd > 0) {
							if( s[i+5].flags & ST_BLUE )
								continue;
							j -= fw;
						} else {
							if( s[i+4].flags & ST_BLUE )
								continue;
							j += fw;
						}
					} else if(fw < 0) {
						if (fd > 0) {
							if( s[i+1].flags & ST_BLUE )
								continue;
							k -= fw;
						} else {
							if( s[i].flags & ST_BLUE )
								continue;
							k += fw;
						}
					}
					pri = ((j - b) - (b - k));

					if (pri > 0) {
						if( s[i+2].flags & ST_BLUE )
							continue;
					} else if(pri < 0) {
						if( s[i+3].flags & ST_BLUE )
							continue;
					}
				}

				/*
				 * first fix up the width of 1st and 3rd
				 * stems
				 */
				if (fw > 0) {
					if (fd > 0) {
						s[i + 5].value -= fw;
						c -= fw;
					} else {
						s[i + 4].value += fw;
						c += fw;
					}
				} else {
					if (fd > 0) {
						s[i + 1].value -= fw;
						a -= fw;
					} else {
						s[i].value += fw;
						a += fw;
					}
				}
				fd = ((c - b) - (b - a));

				if (fd > 0) {
					s[i + 2].value += abs(fd) / 2;
				} else {
					s[i + 3].value -= abs(fd) / 2;
				}

				s[i].flags |= ST_3;
				i += 4;
			}
		}
	}

	return (nnew & ~1);	/* number of lines must be always even */
}

/*
 * these macros and function allow to set the base stem,
 * check that it's not empty and subtract another stem
 * from the base stem (possibly dividing it into multiple parts)
 */

/* pairs for pieces of the base stem */
static short xbstem[MAX_STEMS*2]; 
/* index of the last point */
static int xblast= -1; 

#define setbasestem(from, to) \
	(xbstem[0]=from, xbstem[1]=to, xblast=1)
#define isbaseempty()	(xblast<=0)

/* returns 1 if was overlapping, 0 otherwise */
static int
subfrombase(
	int from,
	int to
) 
{
	int a, b;
	int i, j;

	if(isbaseempty())
		return 0;

	/* handle the simple case simply */
	if(from > xbstem[xblast] || to < xbstem[0])
		return 0;

	/* the binary search may be more efficient */
	/* but for now the linear search is OK */
	for(b=1; from > xbstem[b]; b+=2) {} /* result: from <= xbstem[b] */
	for(a=xblast-1; to < xbstem[a]; a-=2) {} /* result: to >= xbstem[a] */

	/* now the interesting examples are:
	 * (it was hard for me to understand, so I looked at the examples)
	 * 1
	 *     a|-----|          |-----|b   |-----|     |-----|
	 *              f|-----|t
	 * 2
	 *     a|-----|b         |-----|    |-----|     |-----|
	 *      f|--|t
	 * 3
	 *     a|-----|b         |-----|    |-----|     |-----|
	 *           f|-----|t
	 * 4
	 *      |-----|b        a|-----|    |-----|     |-----|
	 *          f|------------|t
	 * 5
	 *      |-----|          |-----|b   |-----|    a|-----|
	 *                   f|-----------------------------|t
	 * 6
	 *      |-----|b         |-----|    |-----|    a|-----|
	 *   f|--------------------------------------------------|t
	 * 7
	 *      |-----|b         |-----|   a|-----|     |-----|
	 *          f|--------------------------|t
	 */

	if(a < b-1) /* hits a gap  - example 1 */
		return 0;

	/* now the subtraction itself */

	if(a==b-1 && from > xbstem[a] && to < xbstem[b]) {
		/* overlaps with only one subrange and splits it - example 2 */
		j=xblast; i=(xblast+=2);
		while(j>=b)
			xbstem[i--]=xbstem[j--];
		xbstem[b]=from-1;
		xbstem[b+1]=to+1;
		return 1;
	/* becomes
	 * 2a
	 *     a|b   ||          |-----|    |-----|     |-----|
