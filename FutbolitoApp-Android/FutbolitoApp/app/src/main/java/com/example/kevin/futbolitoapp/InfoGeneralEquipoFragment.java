package com.example.kevin.futbolitoapp;

import android.app.Activity;
import android.os.AsyncTask;
import android.support.v4.app.Fragment;
import android.os.Bundle;
import android.support.v7.widget.Toolbar;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;

import org.json.JSONException;
import org.json.JSONObject;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.MalformedURLException;
import java.net.URL;

/**
 * Created by j3s on 8/21/16.
 */
public class InfoGeneralEquipoFragment extends Fragment {

    public static final String ID_EQUIPO = "";

    private String nombre;
    private String director;
    private String categoria;
    private String id_equipo;
    private View rootView;
    private String equipo_url = "http://futbolitoapp.herokuapp.com/get_equipo/";
    private String ultimos_partidos_url = "http://futbolitoapp.herokuapp.com/get_ult10partidosequipo/";

    public static InfoGeneralEquipoFragment newInstance(String id) {
        Bundle args = new Bundle();
        args.putString(ID_EQUIPO, id);
        InfoGeneralEquipoFragment fragment = new InfoGeneralEquipoFragment();
        fragment.setArguments(args);
        return fragment;
    }

    public InfoGeneralEquipoFragment() {
        // Required empty public constructor
    }

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        id_equipo = getArguments().getString(ID_EQUIPO);
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        // Inflate the layout for this fragment
        rootView = inflater.inflate(R.layout.fragment_info_general_equipo, container, false);
        new TareaWSInfoEquipo().execute(equipo_url + id_equipo);
        new TareaWSUltimosPartidos().execute(ultimos_partidos_url + id_equipo);
        return rootView;
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
                TextView textView = (TextView) rootView.findViewById(R.id.nombre_equipo);
                textView.setText("Nombre: " + nombre);
                textView = (TextView) rootView.findViewById(R.id.director_equipo);
                textView.setText("Director: " + director);
                textView = (TextView) rootView.findViewById(R.id.categoria_equipo);
                textView.setText("Categoría: " + categoria);
            }
        }
    }

    //Tarea Asincrona para llamar al WS de listado de torneos en segundo plano
    private class TareaWSUltimosPartidos extends AsyncTask<String, Integer, Boolean> {


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
                TextView textView = (TextView) rootView.findViewById(R.id.nombre_equipo);
                textView.setText("Nombre: " + nombre);
                textView = (TextView) rootView.findViewById(R.id.director_equipo);
                textView.setText("Director: " + director);
                textView = (TextView) rootView.findViewById(R.id.categoria_equipo);
                textView.setText("Categoría: " + categoria);
            }
        }
    }
}
