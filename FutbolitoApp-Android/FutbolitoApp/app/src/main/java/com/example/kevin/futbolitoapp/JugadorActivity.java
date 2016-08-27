package com.example.kevin.futbolitoapp;


import android.graphics.Color;
import android.os.AsyncTask;
import android.os.Bundle;
import android.os.Handler;
import android.os.Message;
import android.support.v4.widget.SwipeRefreshLayout;
import android.support.v4.widget.TextViewCompat;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.Toolbar;
import android.view.Menu;
import android.view.MenuItem;
import android.widget.TextView;
import android.widget.Toast;

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
import java.util.Collections;

public class JugadorActivity extends AppCompatActivity implements SwipeRefreshLayout.OnRefreshListener{

    private String jugador_url = "http://futbolitoapp.herokuapp.com/get_jugador/";
    private String nombre, fecha_nac, rol, peso, camiseta, equipo, categoria;
    private Toolbar toolbar;
    private SwipeRefreshLayout jugadorSwipeRefresh;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_jugador);

        toolbar = (Toolbar) findViewById(R.id.toolbar);
        setSupportActionBar(toolbar);

        getSupportActionBar().setDisplayHomeAsUpEnabled(true);

        new TareaWSInfoJugador().execute(jugador_url + getIntent().getStringExtra("ID_J"));
        jugadorSwipeRefresh = (SwipeRefreshLayout)findViewById(R.id.swipejugador);
        jugadorSwipeRefresh.setOnRefreshListener(this);
        jugadorSwipeRefresh.setDistanceToTriggerSync(30);
        jugadorSwipeRefresh.setSize(SwipeRefreshLayout.DEFAULT);
        jugadorSwipeRefresh.setColorSchemeColors(Color.GRAY, Color.GREEN, Color.BLUE,
                Color.RED, Color.CYAN);
    }
    Handler mHandler = new Handler() {
        @Override
        public void handleMessage(Message msg) {
            new TareaWSInfoJugador().execute(jugador_url + getIntent().getStringExtra("ID_J"));
            jugadorSwipeRefresh.postDelayed(new Runnable() {
                @Override
                public void run() {
                    Toast.makeText(getApplicationContext(),
                            "Datos actualizados", Toast.LENGTH_SHORT).show();
                    jugadorSwipeRefresh.setRefreshing(false);
                }
            }, 1000);
        }
    };
    @Override
    public void onRefresh() {
        jugadorSwipeRefresh.postDelayed(new Runnable() {
            @Override
            public void run() {
                jugadorSwipeRefresh.setRefreshing(true);
                mHandler.sendEmptyMessage(0);
            }
        }, 1000);
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
                if(fecha_nac == "null")
                    fecha_nac = "No ingresada";
                rol = obj1.getString("rol");
                if(rol.length() == 0)
                    rol = "Ninguna";
                peso = obj1.getString("peso");
                if(peso == "null")
                    peso = "No ingresado";
                camiseta = obj1.getString("num_camiseta");
                if(camiseta == "null")
                    camiseta = "No asignado";
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
                tvnombre.setText(nombre);
                tvfecha.setText(fecha_nac);
                tvrol.setText(rol);
                tvpeso.setText(peso);
                tvcamiseta.setText(camiseta);
                tvequipo.setText(equipo);
                tvcategoria.setText(categoria);
            }
        }
    }
    public void setTitleActionBar(){
        this.getSupportActionBar().setTitle("Jugador");
    }

}
