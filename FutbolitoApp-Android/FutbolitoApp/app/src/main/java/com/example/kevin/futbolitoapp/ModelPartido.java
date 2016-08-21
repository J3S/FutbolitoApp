package com.example.kevin.futbolitoapp;

/**
 * Created by j3s on 8/21/16.
 */
public class ModelPartido {

    private String id_partido;
    private String fecha_partido;
    private String equipo_local;
    private String equipo_visitante;
    private String gol_local;
    private String gol_visitante;
    private String nombre_torneo;

    public ModelPartido(String id_partido, String fecha_partido, String equipo_local, String equipo_visitante, String gol_local, String gol_visitante, String nombre_torneo) {
        this.id_partido = id_partido;
        this.fecha_partido = fecha_partido;
        this.equipo_local = equipo_local;
        this.equipo_visitante = equipo_visitante;
        this.gol_local = gol_local;
        this.gol_visitante = gol_visitante;
        this.nombre_torneo = nombre_torneo;
    }

    public String get_id_partido() {
        return id_partido;
    }

    public String get_fecha_partido() {
        return fecha_partido;
    }

    public String get_equipo_local() {
        return equipo_local;
    }

    public String get_equipo_visitante() {
        return equipo_visitante;
    }

    public String get_gol_local() {
        return gol_local;
    }

    public String get_gol_visitante() {
        return gol_visitante;
    }

    public String get_nombre_torneo() {
        return nombre_torneo;
    }

}
