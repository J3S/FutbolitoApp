package com.example.kevin.futbolitoapp;

import android.os.Bundle;
import android.app.Activity;

import android.os.AsyncTask;
import android.widget.ArrayAdapter;
import android.widget.ListView;

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


public class TablaPosicionesActivity extends Activity {

    private String torneos_url = "http://futbolitoapp.herokuapp.com/get_torneos";
    private ListView lstTorneos;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_tabla_posiciones);

        lstTorneos = (ListView)findViewById(R.id.lstTorneo);

        new TareaWSListar().execute(torneos_url);
    }

    //Tarea Asincrona para llamar al WS de listado en segundo plano
    private class TareaWSListar extends AsyncTask<String, Integer, Boolean> {

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

                for(int i=0; i<respJSON.length(); i++)
                {
                    JSONObject obj = respJSON.getJSONObject(i);
                    int anioTorneo = obj.getInt("anio");
//                    String categoriaTorneo = obj.getString("categoria");
//                    torneos[i] = "" + anioTorneo + "-" + categoriaTorneo;
                    anios[i] = "" + anioTorneo;
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
                //Rellenamos la lista con los torneos
                ArrayAdapter<String> adaptador =
                        new ArrayAdapter<String>(TablaPosicionesActivity.this,
                                android.R.layout.simple_list_item_1, anios);

                lstTorneos.setAdapter(adaptador);
            }
        }
    }
}
