package com.example.kevin.futbolitoapp;


import android.os.AsyncTask;
import android.os.Bundle;
import android.support.v4.widget.TextViewCompat;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.Toolbar;
import android.view.Menu;
import android.view.MenuItem;
import android.widget.TextView;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.MalformedURLException;
import java.net.URL;

public class JugadorActivity extends AppCompatActivity {

    private String jugador_url = "http://futbolitoapp.herokuapp.com/get_jugador/";
    private String nombre, fecha_nac, rol, peso, camiseta, equipo, categoria;
    private Toolbar toolbar;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_jugador);

        toolbar = (Toolbar) findViewById(R.id.toolbar);
        setSupportActionBar(toolbar);

        getSupportActionBar().setDisplayHomeAsUpEnabled(true);

        new TareaWSInfoJugador().execute(jugador_url + getIntent().getStringExtra("ID_J"));
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        switch (item.getItemId()) {
            case android.R.id.home:
                finish();
                return true;
        }

        return super.onOptionsItemSelected(item);
    }

    public boolean onCreateOptionsMenu(Menu menu) {
        return true;
    }

    //Tarea Asincrona para llamar al WS de listado de torneos en segundo plano
    private class TareaWSInfoJugador extends AsyncTask<String, Integer, Boolean> {


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

                while ((line = reader.readLine()) != null) {
                    buffer.append(line);
                }

                JSONObject obj = new JSONObject(buffer.toString());
                JSONObject obj1 = obj.getJSONObject("info_jugador");
                JSONObject obj2 = obj.getJSONObject("nombre_equipo");
                nombre = obj1.getString("nombres") + " " + obj1.getString("apellidos");
                fecha_nac = obj1.getString("fecha_nac");
                rol = obj1.getString("rol");
                peso = obj1.getString("peso");
                camiseta = obj1.getString("num_camiseta");
                categoria = obj1.getString("categoria");
                equipo = obj2.getString("nombre");

            } catch (MalformedURLException e) {
                e.printStackTrace();
                result = false;
            } catch (IOException e) {
                e.printStackTrace();
                result = false;
            } catch (JSONException e) {
                e.printStackTrace();
                result = false;
            } finally {
                if (connection != null) {
                    connection.disconnect();
                }
                try {
                    if (reader != null) {
                        reader.close();
                    }
                }
                catch (IOException e) {
                    e.printStackTrace();
                }
            }

            return result;
        }

        @Override
        protected void onPostExecute(Boolean result) {
            if (result) {
                setTitleActionBar();
                TextView tvnombre = (TextView)findViewById(R.id.nombre_jugador_ind);
                TextView tvfecha = (TextView)findViewById(R.id.fecha_nac);
                TextView tvrol = (TextView)findViewById(R.id.rol_ju);
                TextView tvpeso = (TextView)findViewById(R.id.peso);
                TextView tvcamiseta = (TextView)findViewById(R.id.camiseta);
                TextView tvequipo = (TextView)findViewById(R.id.equipo_jugador);
                TextView tvcategoria = (TextView)findViewById(R.id.categoria_jugador);
                tvnombre.setText("Nombre: " + nombre);
                tvfecha.setText("Fecha de nacimiento: " + fecha_nac);
                tvrol.setText("Rol: " + rol);
                tvpeso.setText("Peso: " + peso);
                tvcamiseta.setText("# Camiseta: " + camiseta);
                tvequipo.setText("Equipo: " + equipo);
                tvcategoria.setText("Categoría: " + categoria);
            }
        }
    }
    public void setTitleActionBar(){
        this.getSupportActionBar().setTitle(nombre);
    }

}
