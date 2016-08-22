package com.example.kevin.futbolitoapp;

import android.content.Intent;
import android.os.Bundle;

import android.os.AsyncTask;
import android.support.v7.app.AppCompatActivity;
import android.support.v7.widget.Toolbar;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.ListView;
import android.widget.Spinner;
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


public class TablaPosicionesActivity extends AppCompatActivity {

    private String torneos_url = "http://futbolitoapp.herokuapp.com/get_torneos";
    private String tabla_posiciones_url = "http://futbolitoapp.herokuapp.com/get_tablasposicionesanio/";
    private Spinner cmbAnio;
    private Spinner cmbCategoria;
    private String[][] categorias;
    private String[][][] tablasPosiciones;

    private ArrayList<ModelEquipo> equipoList;

    private listviewEquipoAdapter adapter;

    private Toolbar toolbar;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_tabla_posiciones);

        toolbar = (Toolbar) findViewById(R.id.toolbar);
        setSupportActionBar(toolbar);

        getSupportActionBar().setDisplayHomeAsUpEnabled(false);

        cmbAnio = (Spinner)findViewById(R.id.cmbAnio);
        cmbCategoria = (Spinner)findViewById(R.id.cmbCategoria);

        equipoList = new ArrayList<ModelEquipo>();

        new TareaWSListarTorneos().execute(torneos_url);

        cmbAnio.setOnItemSelectedListener(
                new AdapterView.OnItemSelectedListener() {
                    public void onItemSelected(AdapterView<?> parent,
                                               android.view.View v, int position, long id) {
                        ArrayAdapter<String> adaptadorCmbCategoria =
                                new ArrayAdapter<>(TablaPosicionesActivity.this, R.layout.custom_spinner_item, categorias[position]);
                        adaptadorCmbCategoria.setDropDownViewResource(R.layout.custom_spinner_dropdown_item);
                        cmbCategoria.setAdapter(adaptadorCmbCategoria);
                    }

                    public void onNothingSelected(AdapterView<?> parent) {

                    }
                }
        );

        cmbCategoria.setOnItemSelectedListener(
                new AdapterView.OnItemSelectedListener() {
                    public void onItemSelected(AdapterView<?> parent,
                                               android.view.View v, int position, long id) {
                        ListView lview = (ListView)findViewById(R.id.listview);
                        lview.setAdapter(null);
                        inicio_tabla();
                        if (v != null) {
                            if (cmbCategoria.getSelectedItemPosition() == 0) {
                                new TareaWSListarTablaPosiciones().execute(tabla_posiciones_url + cmbAnio.getSelectedItem().toString());
                            } else {
                                addHeaders(cmbCategoria.getSelectedItemPosition());
                                addData(cmbCategoria.getSelectedItemPosition()-1);
                            }
                        }
                        adapter.notifyDataSetChanged();
                    }

                    public void onNothingSelected(AdapterView<?> parent) {

                    }
                }
        );
    }

    //Tarea Asincrona para llamar al WS de listado de torneos en segundo plano
    private class TareaWSListarTorneos extends AsyncTask<String, Integer, Boolean> {

        private String[] anios;

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

                JSONArray respJSON = new JSONArray(buffer.toString());
                anios = new String[respJSON.length()];
                categorias = new String[anios.length][];
                for(int i=0; i<respJSON.length(); i++)
                {
                    JSONObject obj = respJSON.getJSONObject(i);
                    int anioTorneo = obj.getInt("anio");
                    JSONArray categoriasTorneoAnio = obj.getJSONArray("categorias");
                    categorias[i] = new String[categoriasTorneoAnio.length() + 1];
                    anios[i] = "" + anioTorneo;

                    categorias[i][0] = "Todas";

                    for(int j=0; j<categoriasTorneoAnio.length(); j++) {
                        categorias[i][j+1] = categoriasTorneoAnio.getString(j);
                    }
                }
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
                ArrayAdapter<String> adaptadorCmbAnio =
                        new ArrayAdapter<>(TablaPosicionesActivity.this, R.layout.custom_spinner_item, anios);
                adaptadorCmbAnio.setDropDownViewResource(R.layout.custom_spinner_dropdown_item);
                cmbAnio.setAdapter(adaptadorCmbAnio);

                ArrayAdapter<String> adaptadorCmbCategoria =
                        new ArrayAdapter<>(TablaPosicionesActivity.this, R.layout.custom_spinner_item, categorias[0]);
                adaptadorCmbCategoria.setDropDownViewResource(R.layout.custom_spinner_dropdown_item);
                cmbCategoria.setAdapter(adaptadorCmbCategoria);
            } else {
                cmbAnio.setAdapter(null);
            }
        }
    }

    //Tarea Asincrona para llamar al WS de listado de torneos en segundo plano
    private class TareaWSListarTablaPosiciones extends AsyncTask<String, Integer, Boolean> {

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

                JSONArray respJSON = new JSONArray(buffer.toString());
                tablasPosiciones = new String[respJSON.length()][][];
                for(int i=0; i<respJSON.length(); i++)
                {
                    JSONObject obj = respJSON.getJSONObject(i);
                    JSONArray resultados = obj.getJSONArray("resultados");
                    tablasPosiciones[i] = new String[resultados.length()][];

                    for(int j=0; j<resultados.length(); j++) {
                        JSONObject objInfo = resultados.getJSONObject(j);
                        String nombreEquipo = objInfo.getString("equipo");
                        String partidosJugados = objInfo.getString("PJ");
                        String partidosGanados = objInfo.getString("PG");
                        String partidosEmpatados = objInfo.getString("PE");
                        String partidosPerdidos = objInfo.getString("PP");
                        String golesFavor = objInfo.getString("GF");
                        String golesContra = objInfo.getString("GC");
                        String golesDiferencia = objInfo.getString("GD");
                        String puntos = objInfo.getString("PTS");
                        String id = objInfo.getString("ID");
                        tablasPosiciones[i][j] = new String[objInfo.length()];
                        tablasPosiciones[i][j][0] = nombreEquipo;
                        tablasPosiciones[i][j][1] = partidosJugados;
                        tablasPosiciones[i][j][2] = partidosGanados;
                        tablasPosiciones[i][j][3] = partidosEmpatados;
                        tablasPosiciones[i][j][4] = partidosPerdidos;
                        tablasPosiciones[i][j][5] = golesFavor;
                        tablasPosiciones[i][j][6] = golesContra;
                        tablasPosiciones[i][j][7] = golesDiferencia;
                        tablasPosiciones[i][j][8] = puntos;
                        tablasPosiciones[i][j][9] = id;
                    }
                }
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
                inicio_tabla();
                for (int i=1; i<categorias[cmbAnio.getSelectedItemPosition()].length;i++) {
                    addHeaders(i);
                    addData(i-1);
                }
                adapter.notifyDataSetChanged();
            } else {
                cmbCategoria.setAdapter(null);
            }
        }
    }

    public void inicio_tabla() {
        ListView lview = (ListView)findViewById(R.id.listview);
        adapter = new listviewEquipoAdapter(this, "", 0);
        lview.setAdapter(adapter);
    }
    public void addHeaders(int index){
        String nombre_categoria = cmbCategoria.getItemAtPosition(index).toString();
        adapter.addSectionHeaderItem(nombre_categoria);
    }

    /** Agregar los datos a la tabla **/
    public void addData(int index) {

        int limite = tablasPosiciones[index].length;
        for (int i=0; i<limite; i++) {
            adapter.addItem(tablasPosiciones[index][i][0], tablasPosiciones[index][i][1], tablasPosiciones[index][i][2], tablasPosiciones[index][i][3], tablasPosiciones[index][i][4],
                    tablasPosiciones[index][i][5], tablasPosiciones[index][i][6], tablasPosiciones[index][i][7], tablasPosiciones[index][i][8], tablasPosiciones[index][i][9]);
        }
        ListView lview = (ListView)findViewById(R.id.listview);
        lview.setOnItemClickListener(new AdapterView.OnItemClickListener() {

            @Override
            public void onItemClick(AdapterView<?> parent, View view,
                                    int position, long id) {

                if(((TextView)view.findViewById(R.id.id_equipo)).getText().toString() != "") {
                    //Creamos el Intent
                    Intent intent = new Intent(TablaPosicionesActivity.this, EquipoActivity.class);
                    //Creamos la información a pasar entre actividades
                    Bundle b = new Bundle();
                    b.putString("ID", ((TextView) view.findViewById(R.id.id_equipo)).getText().toString());
                    //Añadimos la información al intent
                    intent.putExtras(b);
                    //Iniciamos la nueva actividad
                    startActivity(intent);
                }
            }
        });
    }
}
