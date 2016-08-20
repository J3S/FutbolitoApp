package com.example.kevin.futbolitoapp;

/**
 * Created by j3s on 8/20/16.
 */
public class ModelEquipo {

    private String nom_equipo;
    private String pj_equipo;
    private String pg_equipo;
    private String pe_equipo;
    private String pp_equipo;
    private String gf_equipo;
    private String gc_equipo;
    private String gd_equipo;
    private String pts_equipo;

    public ModelEquipo(String nom_equipo, String pj_equipo, String pg_equipo, String pe_equipo, String pp_equipo, String gf_equipo, String gc_equipo, String gd_equipo, String pts_equipo) {
        this.nom_equipo = nom_equipo;
        this.pj_equipo = pj_equipo;
        this.pg_equipo = pg_equipo;
        this.pe_equipo = pe_equipo;
        this.pp_equipo = pp_equipo;
        this.gf_equipo = gf_equipo;
        this.gc_equipo = gc_equipo;
        this.gd_equipo = gd_equipo;
        this.pts_equipo = pts_equipo;
    }

    public String get_nom_equipo() {
        return nom_equipo;
    }

    public String get_pj_equipo() {
        return pj_equipo;
    }

    public String get_pg_equipo() {
        return pg_equipo;
    }

    public String get_pe_equipo() {
        return pe_equipo;
    }

    public String get_pp_equipo() {
        return pp_equipo;
    }

    public String get_gf_equipo() {
        return gf_equipo;
    }

    public String get_gc_equipo() {
        return gc_equipo;
    }

    public String get_gd_equipo() {
        return gd_equipo;
    }

    public String get_pts_equipo() {
        return pts_equipo;
    }
}
