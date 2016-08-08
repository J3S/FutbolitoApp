package com.example.kevin.futbolitoapp;

import android.graphics.Color;
import android.graphics.Typeface;
import android.os.Bundle;

import android.os.AsyncTask;
import android.support.v7.app.AppCompatActivity;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.Spinner;
import android.widget.TableLayout;
import android.widget.TableRow;
import android.widget.TextView;
import android.widget.TableRow.LayoutParams;

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


public class TablaPosicionesActivity extends AppCompatActivity {

    private String torneos_url = "http://futbolitoapp.herokuapp.com/get_torneos";
    private String tabla_posiciones_url = "http://futbolitoapp.herokuapp.com/get_tablasposicionesanio/";
    private Spinner cmbAnio;
    private Spinner cmbCategoria;
    private String[][] categorias;
    private String[][][] tablasPosiciones;

    private TableLayout tablaP;
    TableRow tr;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_tabla_posiciones);

        cmbAnio = (Spinner)findViewById(R.id.cmbAnio);
        cmbCategoria = (Spinner)findViewById(R.id.cmbCategoria);
        tablaP = (TableLayout)findViewById(R.id.tablaposiciones);

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
                        int numelem = cmbCategoria.getAdapter().getCount();
                        if (v != null) {
                            if (cmbCategoria.getSelectedItemPosition() == 0) {
                                tablaP.removeAllViews();
                                new TareaWSListarTablaPosiciones().execute(tabla_posiciones_url + cmbAnio.getSelectedItem().toString());
                            } else {
                                tablaP.removeAllViews();
                                addHeaders(cmbCategoria.getSelectedItemPosition());
                                addData(cmbCategoria.getSelectedItemPosition()-1);
                            }
                        }
                    }

                    public void onNothingSelected(AdapterView<?> parent) {

                    }
                }
        );

    }

    /** Agregar los headers a la tabla **/
    public void addHeaders(int index) {

        /** Creando un TableRow dinámicamente **/
        tr = new TableRow(this);
        tr.setLayoutParams(new LayoutParams(
                LayoutParams.MATCH_PARENT,
                LayoutParams.WRAP_CONTENT
        ));

        /** Creando los TextViews para agregarlo al TableRow **/
        TextView cat = new TextView(this);
        cat.setText(categorias[0][index]);
        cat.setTextColor(Color.RED);
        cat.setTypeface(Typeface.DEFAULT, Typeface.BOLD);
        cat.setLayoutParams(new LayoutParams(
                LayoutParams.MATCH_PARENT,
                LayoutParams.WRAP_CONTENT
        ));
        cat.setPadding(5,5,0,0);
        tr.addView(cat);

        tablaP.addView(tr, new TableLayout.LayoutParams(
                LayoutParams.MATCH_PARENT,
                LayoutParams.WRAP_CONTENT
        ));

        /** Creando un TableRow dinámicamente **/
        tr = new TableRow(this);
        tr.setLayoutParams(new LayoutParams(
                LayoutParams.MATCH_PARENT,
                LayoutParams.WRAP_CONTENT
        ));

        /** Creando los TextViews para agregarlo al TableRow **/
        TextView equipo = new TextView(this);
        equipo.setText("Equipo");
        equipo.setTextColor(Color.GRAY);
        equipo.setTypeface(Typeface.DEFAULT, Typeface.BOLD);
        equipo.setLayoutParams(new LayoutParams(
                LayoutParams.MATCH_PARENT,
                LayoutParams.WRAP_CONTENT
        ));
        equipo.setPadding(5,5,0,0);
        tr.addView(equipo);

        TextView pj = new TextView(this);
        pj.setText("PJ");
        pj.setTextColor(Color.GRAY);
        pj.setTypeface(Typeface.DEFAULT, Typeface.BOLD);
        pj.setLayoutParams(new LayoutParams(
                LayoutParams.MATCH_PARENT,
                LayoutParams.WRAP_CONTENT
        ));
        pj.setPadding(5,5,0,0);
        tr.addView(pj);

        TextView pg = new TextView(this);
        pg.setText("PG");
        pg.setTextColor(Color.GRAY);
        pg.setTypeface(Typeface.DEFAULT, Typeface.BOLD);
        pg.setLayoutParams(new LayoutParams(
                LayoutParams.MATCH_PARENT,
                LayoutParams.WRAP_CONTENT
        ));
        pg.setPadding(5,5,0,0);
        tr.addView(pg);

        TextView pe = new TextView(this);
        pe.setText("PE");
        pe.setTextColor(Color.GRAY);
        pe.setTypeface(Typeface.DEFAULT, Typeface.BOLD);
        pe.setLayoutParams(new LayoutParams(
                LayoutParams.MATCH_PARENT,
                LayoutParams.WRAP_CONTENT
        ));
        pe.setPadding(5,5,0,0);
        tr.addView(pe);

        TextView pp = new TextView(this);
        pp.setText("PP");
        pp.setTextColor(Color.GRAY);
        pp.setTypeface(Typeface.DEFAULT, Typeface.BOLD);
        pp.setLayoutParams(new LayoutParams(
                LayoutParams.MATCH_PARENT,
                LayoutParams.WRAP_CONTENT
        ));
        pp.setPadding(5,5,0,0);
        tr.addView(pp);

        TextView gf = new TextView(this);
        gf.setText("GF");
        gf.setTextColor(Color.GRAY);
        gf.setTypeface(Typeface.DEFAULT, Typeface.BOLD);
        gf.setLayoutParams(new LayoutParams(
                LayoutParams.MATCH_PARENT,
                LayoutParams.WRAP_CONTENT
        ));
        gf.setPadding(5,5,0,0);
        tr.addView(gf);

        TextView gc = new TextView(this);
        gc.setText("GC");
        gc.setTextColor(Color.GRAY);
        gc.setTypeface(Typeface.DEFAULT, Typeface.BOLD);
        gc.setLayoutParams(new LayoutParams(
                LayoutParams.MATCH_PARENT,
                LayoutParams.WRAP_CONTENT
        ));
        gc.setPadding(5,5,0,0);
        tr.addView(gc);

        TextView gd = new TextView(this);
        gd.setText("GD");
        gd.setTextColor(Color.GRAY);
        gd.setTypeface(Typeface.DEFAULT, Typeface.BOLD);
        gd.setLayoutParams(new LayoutParams(
                LayoutParams.MATCH_PARENT,
                LayoutParams.WRAP_CONTENT
        ));
        gd.setPadding(5,5,0,0);
        tr.addView(gd);

        TextView pts = new TextView(this);
        pts.setText("Puntos");
        pts.setTextColor(Color.GRAY);
        pts.setTypeface(Typeface.DEFAULT, Typeface.BOLD);
        pts.setLayoutParams(new LayoutParams(
                LayoutParams.MATCH_PARENT,
                LayoutParams.WRAP_CONTENT
        ));
        pts.setPadding(5,5,0,0);
        tr.addView(pts);

        tablaP.addView(tr, new TableLayout.LayoutParams(
                LayoutParams.MATCH_PARENT,
                LayoutParams.WRAP_CONTENT
        ));
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
                for (int i=1; i<categorias[0].length;i++) {
                    addHeaders(i);
                    addData(i-1);
                }

            }
        }
    }

    public void addData(int index) {
        int limite = tablasPosiciones[index].length;
        for (int i=0; i<limite; i++) {
            /** Creando un TableRow dinámicamente **/
            tr = new TableRow(this);
            tr.setLayoutParams(new LayoutParams(
                    LayoutParams.MATCH_PARENT,
                    LayoutParams.WRAP_CONTENT
            ));

            /** Creando los TextViews para agregarlo al TableRow **/
            TextView equipo = new TextView(this);
            equipo.setText(tablasPosiciones[index][i][0]);
            equipo.setTextColor(Color.BLUE);
            equipo.setTypeface(Typeface.DEFAULT, Typeface.BOLD);
            equipo.setLayoutParams(new LayoutParams(
                    LayoutParams.MATCH_PARENT,
                    LayoutParams.WRAP_CONTENT
            ));
            equipo.setPadding(5,5,0,0);
            tr.addView(equipo);

            TextView pj = new TextView(this);
            pj.setText(tablasPosiciones[index][i][1]);
            pj.setTextColor(Color.BLUE);
            pj.setTypeface(Typeface.DEFAULT, Typeface.BOLD);
            pj.setLayoutParams(new LayoutParams(
                    LayoutParams.MATCH_PARENT,
                    LayoutParams.WRAP_CONTENT
            ));
            pj.setPadding(5,5,0,0);
            tr.addView(pj);

            TextView pg = new TextView(this);
            pg.setText(tablasPosiciones[index][i][2]);
            pg.setTextColor(Color.BLUE);
            pg.setTypeface(Typeface.DEFAULT, Typeface.BOLD);
            pg.setLayoutParams(new LayoutParams(
                    LayoutParams.MATCH_PARENT,
                    LayoutParams.WRAP_CONTENT
            ));
            pg.setPadding(5,5,0,0);
            tr.addView(pg);

            TextView pe = new TextView(this);
            pe.setText(tablasPosiciones[index][i][3]);
            pe.setTextColor(Color.BLUE);
            pe.setTypeface(Typeface.DEFAULT, Typeface.BOLD);
            pe.setLayoutParams(new LayoutParams(
                    LayoutParams.MATCH_PARENT,
                    LayoutParams.WRAP_CONTENT
            ));
            pe.setPadding(5,5,0,0);
            tr.addView(pe);

            TextView pp = new TextView(this);
            pp.setText(tablasPosiciones[index][i][4]);
            pp.setTextColor(Color.BLUE);
            pp.setTypeface(Typeface.DEFAULT, Typeface.BOLD);
            pp.setLayoutParams(new LayoutParams(
                    LayoutParams.MATCH_PARENT,
                    LayoutParams.WRAP_CONTENT
            ));
            pp.setPadding(5,5,0,0);
            tr.addView(pp);

            TextView gf = new TextView(this);
            gf.setText(tablasPosiciones[index][i][5]);
            gf.setTextColor(Color.BLUE);
            gf.setTypeface(Typeface.DEFAULT, Typeface.BOLD);
            gf.setLayoutParams(new LayoutParams(
                    LayoutParams.MATCH_PARENT,
                    LayoutParams.WRAP_CONTENT
            ));
            gf.setPadding(5,5,0,0);
            tr.addView(gf);

            TextView gc = new TextView(this);
            gc.setText(tablasPosiciones[index][i][6]);
            gc.setTextColor(Color.BLUE);
            gc.setTypeface(Typeface.DEFAULT, Typeface.BOLD);
            gc.setLayoutParams(new LayoutParams(
                    LayoutParams.MATCH_PARENT,
                    LayoutParams.WRAP_CONTENT
            ));
            gc.setPadding(5,5,0,0);
            tr.addView(gc);

            TextView gd = new TextView(this);
            gd.setText(tablasPosiciones[index][i][7]);
            gd.setTextColor(Color.BLUE);
            gd.setTypeface(Typeface.DEFAULT, Typeface.BOLD);
            gd.setLayoutParams(new LayoutParams(
                    LayoutParams.MATCH_PARENT,
                    LayoutParams.WRAP_CONTENT
            ));
            gd.setPadding(5,5,0,0);
            tr.addView(gd);

            TextView pts = new TextView(this);
            pts.setText(tablasPosiciones[index][i][8]);
            pts.setTextColor(Color.BLUE);
            pts.setTypeface(Typeface.DEFAULT, Typeface.BOLD);
            pts.setLayoutParams(new LayoutParams(
                    LayoutParams.MATCH_PARENT,
                    LayoutParams.WRAP_CONTENT
            ));
            pts.setPadding(5,5,0,0);
            tr.addView(pts);

            tablaP.addView(tr, new TableLayout.LayoutParams(
                    LayoutParams.MATCH_PARENT,
                    LayoutParams.WRAP_CONTENT
            ));
        }
    }
}
