package com.example.kevin.futbolitoapp;

/**
 * Created by j3s on 8/21/16.
 */
public class ModelJugador {
    private String nom_jugador;
    private String rol_jugador;
    private String camis_jugador;
    private String id;

    public ModelJugador(String nom_jugador, String rol_jugador, String camis_jugador, String id) {
        this.nom_jugador = nom_jugador;
        this.rol_jugador = rol_jugador;
        this.camis_jugador = camis_jugador;
        this.id = id;
    }

    public String get_nom_jugador() {
        return nom_jugador;
    }

    public String get_rol_jugador() {
        return rol_jugador;
    }

    public String get_camis_jugador() {
        return camis_jugador;
    }

    public String get_id_jugador() {
        return id;
    }
}
