package com.example.kevin.futbolitoapp;

import android.os.Bundle;

import android.os.AsyncTask;
import android.support.v7.app.AppCompatActivity;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.Spinner;

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

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_tabla_posiciones);

        cmbAnio = (Spinner)findViewById(R.id.cmbAnio);
        cmbCategoria = (Spinner)findViewById(R.id.cmbCategoria);

        new TareaWSListarTorneos().execute(torneos_url);
        new TareaWSListarTablaPosiciones().execute(tabla_posiciones_url + cmbAnio.getSelectedItem().toString());

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

        private String[] tablaPosicion;

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
                tablaPosicion = new String[respJSON.length()];
//                categorias = new String[categoriaTorneo.length][];
                for(int i=0; i<respJSON.length(); i++)
                {
                    JSONObject obj = respJSON.getJSONObject(i);
                    String categoriaTorneo = obj.getString("categoria");
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
}
