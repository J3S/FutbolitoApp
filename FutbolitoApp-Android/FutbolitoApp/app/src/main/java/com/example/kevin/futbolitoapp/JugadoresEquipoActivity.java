package com.example.kevin.futbolitoapp;

import android.content.res.Resources;
import android.os.AsyncTask;
import android.os.Bundle;
import android.support.v7.app.AppCompatActivity;
import android.widget.TextView;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.MalformedURLException;
import java.net.URL;


public class JugadoresEquipoActivity extends AppCompatActivity {

    private String jugadores_equipo_url = "http://futbolitoapp.herokuapp.com/get_jugadores_equipo/";
    private TextView lbl_nombre_equipo;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_jugadores_equipo);

        lbl_nombre_equipo = (TextView)findViewById(R.id.lbl_nombre_equipo);
        Resources res = getResources();
        String text = String.format(res.getString(R.string.nombre_equipo), "aaa12");
        lbl_nombre_equipo.setText(text);
    }

    private class TareaWSListarJugadores extends AsyncTask<String, Integer, Boolean> {

        protected Boolean doInBackground(String... params) {
            boolean result = true;
            HttpURLConnection connection = null;
            BufferedReader reader = null;

            try {
                URL url = new URL(params[0]);
                connection = (HttpURLConnection) url.openConnection();
                connection.connect();

                InputStream stream = connection.getInputStream();
                reader = new BufferedReader(new InputStreamReader(stream));

                StringBuffer buffer = new StringBuffer();
                String line = "";

                while ((line = reader.readLine()) != null ) {
                    buffer.append(line);
                }

            } catch (MalformedURLException e) {
                e.printStackTrace();
                result = false;
            } catch (IOException e) {
                e.printStackTrace();
                result = false;
            }

            return result;

        }

    }
}
