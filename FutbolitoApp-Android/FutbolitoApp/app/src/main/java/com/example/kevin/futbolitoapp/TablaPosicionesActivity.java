package com.example.kevin.futbolitoapp;

import android.content.Intent;
import android.graphics.Color;
import android.graphics.Typeface;
import android.os.Bundle;

import android.os.AsyncTask;
import android.support.v7.app.ActionBar;
import android.support.v7.app.AppCompatActivity;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.LinearLayout;
import android.widget.ListView;
import android.widget.Spinner;
import android.widget.TableLayout;
import android.widget.TableRow;
import android.widget.TextView;
import android.widget.TableRow.LayoutParams;
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
import java.util.ArrayList;


public class TablaPosicionesActivity extends AppCompatActivity {

    private String torneos_url = "http://futbolitoapp.herokuapp.com/get_torneos";
    private String tabla_posiciones_url = "http://futbolitoapp.herokuapp.com/get_tablasposicionesanio/";
    private Spinner cmbAnio;
    private Spinner cmbCategoria;
    private String[][] categorias;
    private String[][][] tablasPosiciones;

    private ArrayList<ModelEquipo> equipoList;

    private TableLayout tablaP;
    TableRow tr;
    private listviewEquipoAdapter adapter;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_tabla_posiciones);

        cmbAnio = (Spinner)findViewById(R.id.cmbAnio);
        cmbCategoria = (Spinner)findViewById(R.id.cmbCategoria);
//        tablaP = (TableLayout)findViewById(R.id.tablaposiciones);

        equipoList = new ArrayList<ModelEquipo>();

        new TareaWSListarTorneos().execute(torneos_url);

        cmbAnio.setOnItemSelectedListener(
                new AdapterView.OnItemSelectedListener() {
                    public void onItemSelected(AdapterView<?> parent,
                                               android.view.View v, int position, long id) {
                        ArrayAdapter<String> adaptadorCmbCategoria =
                                new ArrayAdapter<>(TablaPosicionesActivity.this, android.R.layout.simple_spinner_item, categorias[position]);
                        adaptadorCmbCategoria.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
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
                        if (v != null) {
                            if (cmbCategoria.getSelectedItemPosition() == 0) {
//                                tablaP.removeAllViews();
                                new TareaWSListarTablaPosiciones().execute(tabla_posiciones_url + cmbAnio.getSelectedItem().toString());
                            } else {
//                                tablaP.removeAllViews();
//                                addHeaders(cmbCategoria.getSelectedItemPosition());
                                addData(cmbCategoria.getSelectedItemPosition()-1);
                            }
                        }
                    }

                    public void onNothingSelected(AdapterView<?> parent) {

                    }
                }
        );
    }

    private void populateList(String nom_equipo, String pj_equipo, String pg_equipo, String pe_equipo, String pp_equipo, String gf_equipo, String gc_equipo, String gd_equipo, String pts_equipo) {
        ModelEquipo item;

        item = new ModelEquipo(nom_equipo, pj_equipo, pg_equipo, pe_equipo, pp_equipo, gf_equipo, gc_equipo, gd_equipo, pts_equipo);
        equipoList.add(item);
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
                        new ArrayAdapter<>(TablaPosicionesActivity.this, android.R.layout.simple_spinner_item, anios);
                adaptadorCmbAnio.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
                cmbAnio.setAdapter(adaptadorCmbAnio);

                ArrayAdapter<String> adaptadorCmbCategoria =
                        new ArrayAdapter<>(TablaPosicionesActivity.this, android.R.layout.simple_spinner_item, categorias[0]);
                adaptadorCmbCategoria.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
                cmbCategoria.setAdapter(adaptadorCmbCategoria);
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
                for (int i=1; i<categorias[0].length;i++) {
                    addHeaders();
                    addData(i-1);
                }
                adapter.notifyDataSetChanged();
            }
        }
    }

    public void inicio_tabla() {
        ListView lview = (ListView)findViewById(R.id.listview);
        adapter = new listviewEquipoAdapter(this);
        lview.setAdapter(adapter);
    }
    public void addHeaders(){
        adapter.addSectionHeaderItem();
    }

    /** Agregar los datos a la tabla **/
    public void addData(int index) {
//        LinearLayout linearLayoutLista = (LinearLayout)findViewById(R.id.relativeLayout1);
//        ListView lview = new ListView(this);
//        ListView lview = (ListView)findViewById(R.id.listview);
//        lview.setLayoutParams(new LayoutParams(
//                LayoutParams.MATCH_PARENT,
//                LayoutParams.WRAP_CONTENT
//        ));
//        listviewEquipoAdapter adapter = new listviewEquipoAdapter(this);
//        adapter.addSectionHeaderItem();
//        lview.setAdapter(adapter);
//        linearLayoutLista.addView(lview);
//
//        LayoutInflater inflater = this.getLayoutInflater();
//        View header_tabla = inflater.inflate(R.layout.listview_row_header_equipo, null);
//        linearLayoutLista.addView(header_tabla);
//        ViewGroup header = (ViewGroup)inflater.inflate(R.layout.listview_row_header_equipo,linearLayoutLista,false);
//        lview.addHeaderView(header);
        int limite = tablasPosiciones[index].length;
//        adapter = new listviewEquipoAdapter(this);
        for (int i=0; i<limite; i++) {
            adapter.addItem(tablasPosiciones[index][i][0], tablasPosiciones[index][i][1], tablasPosiciones[index][i][2], tablasPosiciones[index][i][3], tablasPosiciones[index][i][4],
                    tablasPosiciones[index][i][5], tablasPosiciones[index][i][6], tablasPosiciones[index][i][7], tablasPosiciones[index][i][8]);
//            populateList(tablasPosiciones[index][i][0], tablasPosiciones[index][i][1], tablasPosiciones[index][i][2], tablasPosiciones[index][i][3], tablasPosiciones[index][i][4],
//                    tablasPosiciones[index][i][5], tablasPosiciones[index][i][6], tablasPosiciones[index][i][7], tablasPosiciones[index][i][8]);
        }
//        lview.setAdapter(adapter);
//        adapter.notifyDataSetChanged();
//        lview.setOnItemClickListener(new AdapterView.OnItemClickListener() {
//
//            @Override
//            public void onItemClick(AdapterView<?> parent, View view,
//                                    int position, long id) {
//
//                String nom_equipo = ((TextView)view.findViewById(R.id.nom_equipo)).getText().toString();
//                String pj_equipo = ((TextView)view.findViewById(R.id.pj_equipo)).getText().toString();
//                String pg_equipo = ((TextView)view.findViewById(R.id.pg_equipo)).getText().toString();
//                String pe_equipo = ((TextView)view.findViewById(R.id.pe_equipo)).getText().toString();
//                String pp_equipo = ((TextView)view.findViewById(R.id.pp_equipo)).getText().toString();
//
//                Toast.makeText(getApplicationContext(),
//                        "Equipo : " + nom_equipo +"\n"
//                                +"PJ : " + pj_equipo +"\n"
//                                +"PG : " +pg_equipo +"\n"
//                                +"PE : " +pe_equipo +"\n"
//                                +"PP : " +pp_equipo, Toast.LENGTH_SHORT).show();
//            }
//        });
//        lview.setAdapter(adapter);
//        linearLayoutLista.addView(lview);
    }
}
