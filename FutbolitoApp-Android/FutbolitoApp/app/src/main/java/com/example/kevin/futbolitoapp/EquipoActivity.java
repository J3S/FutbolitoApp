package com.example.kevin.futbolitoapp;

import android.content.Intent;
import android.graphics.Color;
import android.graphics.Typeface;
import android.os.AsyncTask;
import android.os.Bundle;
import android.support.design.widget.TabLayout;
import android.support.v4.app.FragmentManager;
import android.support.v4.app.Fragment;
import android.support.v4.app.FragmentPagerAdapter;
import android.support.v4.view.ViewPager;
import android.support.v7.app.ActionBar;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.Toolbar;
import android.view.LayoutInflater;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.TableLayout;
import android.widget.TableRow;
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
import java.util.ArrayList;
import java.util.List;

public class EquipoActivity extends AppCompatActivity {

    private String equipo_url = "http://futbolitoapp.herokuapp.com/get_equipo/";
    private String jugadores_url = "http://futbolitoapp.herokuapp.com/get_jugadores_equipo/";
    private String nombre;
    private String director;
    private String categoria;


    private TableLayout tablaJ;
    TableRow tr;

    private Toolbar toolbar;
    private TabLayout tabLayout;
    private ViewPager viewPager;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_equipo);

        toolbar = (Toolbar) findViewById(R.id.toolbar);
        setSupportActionBar(toolbar);

        getSupportActionBar().setDisplayHomeAsUpEnabled(true);

        // Get the ViewPager and set it's PagerAdapter so that it can display items
        ViewPager viewPager = (ViewPager) findViewById(R.id.viewpager);
        viewPager.setAdapter(new FragmentEquipoPagerAdapter(getSupportFragmentManager(), EquipoActivity.this, getIntent().getStringExtra("ID")));
        // Give the TabLayout the ViewPager
        TabLayout tabLayout = (TabLayout) findViewById(R.id.tabs);
        tabLayout.setupWithViewPager(viewPager);

        new TareaWSInfoEquipo().execute(equipo_url + getIntent().getStringExtra("ID"));
//        new TareaWSListarJugadores().execute(jugadores_url + getIntent().getStringExtra("ID"));
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
    private class TareaWSInfoEquipo extends AsyncTask<String, Integer, Boolean> {


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
                nombre = obj.getString("nombre");
                director = obj.getString("director_tecnico");
                categoria = obj.getString("categoria");

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
                setTitleActionBar(nombre);
            }
        }
    }

    public void setTitleActionBar(String title){
        this.getSupportActionBar().setTitle("Equipo " + nombre);
    }



    /** Agregar los datos a la tabla **/
//    public void addData(int index) {
//            /** Creando un TableRow dinámicamente **/
//            tr = new TableRow(this);
//            tr.setPadding(0,20,0,20);
//            tr.setId(Integer.parseInt(infoJugador[index][0]));
//            tr.setClickable(true);
//            tr.setOnClickListener(new View.OnClickListener() {
//                public void onClick(View v) {
//                    //Creamos el Intent
//                    Intent intent =
//                            new Intent(EquipoActivity.this, EquipoActivity.class);
//
//                    //Creamos la información a pasar entre actividades
//                    Bundle b = new Bundle();
//                    b.putString("ID", String.valueOf(v.getId()));
//
////                    Añadimos la información al intent
//                    intent.putExtras(b);
//
//                    //Iniciamos la nueva actividad
//                    startActivity(intent);
//                }
//            });
//            if(index%2 == 0)
//                tr.setBackgroundColor(Color.LTGRAY);
//            tr.setLayoutParams(new TableRow.LayoutParams(
//                    TableRow.LayoutParams.MATCH_PARENT,
//                    TableRow.LayoutParams.WRAP_CONTENT
//            ));
//
//            /** Creando los TextViews para agregarlo al TableRow **/
//            TextView equipo = new TextView(this);
//            equipo.setText(infoJugador[index][1] + " " + infoJugador[index][2]);
//            equipo.setTextColor(Color.BLUE);
//            equipo.setTypeface(Typeface.DEFAULT, Typeface.BOLD);
//            equipo.setLayoutParams(new TableRow.LayoutParams(
//                    TableRow.LayoutParams.MATCH_PARENT,
//                    TableRow.LayoutParams.WRAP_CONTENT
//            ));
//            equipo.setPadding(10,5,10,0);
//            tr.addView(equipo);
//
//            TextView pg = new TextView(this);
//            pg.setText(infoJugador[index][3]);
//            pg.setTextColor(Color.BLUE);
//            pg.setTypeface(Typeface.DEFAULT, Typeface.BOLD);
//            pg.setLayoutParams(new TableRow.LayoutParams(
//                    TableRow.LayoutParams.MATCH_PARENT,
//                    TableRow.LayoutParams.WRAP_CONTENT
//            ));
//            pg.setPadding(10,5,10,0);
//            tr.addView(pg);
//
//            TextView pe = new TextView(this);
//            pe.setText(infoJugador[index][4]);
//            pe.setTextColor(Color.BLUE);
//            pe.setTypeface(Typeface.DEFAULT, Typeface.BOLD);
//            pe.setLayoutParams(new TableRow.LayoutParams(
//                    TableRow.LayoutParams.MATCH_PARENT,
//                    TableRow.LayoutParams.WRAP_CONTENT
//            ));
//            pe.setPadding(10,5,10,0);
//            tr.addView(pe);
//
//            tablaJ.addView(tr, new TableLayout.LayoutParams(
//                    TableRow.LayoutParams.MATCH_PARENT,
//                    TableRow.LayoutParams.WRAP_CONTENT
//            ));
//    }
}
