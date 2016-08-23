package com.example.kevin.futbolitoapp;

import android.content.Intent;
import android.os.AsyncTask;
import android.support.v4.app.Fragment;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.ListView;
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

/**
 * Created by j3s on 8/21/16.
 */
public class PosicionEquipoFragment extends Fragment {

    public static final String ID_EQUIPO = "";
    private String tabla_pos_equipo_url = "http://futbolitoapp.herokuapp.com/get_ultima_participacion/";

    private String id_equipo;
    private View rootView;
    private String tablaPosiciones[][];
    private listviewEquipoAdapter adapter;
    private String header_tabla;

    public static PosicionEquipoFragment newInstance(String id) {
        Bundle args = new Bundle();
        args.putString(ID_EQUIPO, id);
        PosicionEquipoFragment fragment = new PosicionEquipoFragment();
        fragment.setArguments(args);
        return fragment;
    }

    public PosicionEquipoFragment() {
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
        rootView = inflater.inflate(R.layout.fragment_posicion_equipo, container, false);
        new TareaWSListarTablaPosiciones().execute(tabla_pos_equipo_url + id_equipo);
        return rootView;
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
                JSONObject obj = new JSONObject(buffer.toString());
                JSONArray resultados = obj.getJSONArray("resultados");
                header_tabla = obj.getString("categoria") + " " + obj.getString("anio");
                tablaPosiciones = new String[resultados.length()][];
                int pos_equipo = 1;
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
                    tablaPosiciones[j] = new String[objInfo.length()+1];
                    tablaPosiciones[j][0] = nombreEquipo;
                    tablaPosiciones[j][1] = partidosJugados;
                    tablaPosiciones[j][2] = partidosGanados;
                    tablaPosiciones[j][3] = partidosEmpatados;
                    tablaPosiciones[j][4] = partidosPerdidos;
                    tablaPosiciones[j][5] = golesFavor;
                    tablaPosiciones[j][6] = golesContra;
                    tablaPosiciones[j][7] = golesDiferencia;
                    tablaPosiciones[j][8] = puntos;
                    tablaPosiciones[j][9] = id;
                    tablaPosiciones[j][10] = String.valueOf(pos_equipo);
                    pos_equipo++;
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
                addHeaders();
                addData();
                adapter.notifyDataSetChanged();
            }
        }
    }

    public void inicio_tabla() {
        ListView lview = (ListView) rootView.findViewById(R.id.listviewTablaPosIndividual);
        adapter = new listviewEquipoAdapter(rootView.getContext(), id_equipo, 1);
        lview.setAdapter(adapter);
    }

    public void addHeaders(){
        adapter.addSectionHeaderItem(header_tabla);
    }
    /** Agregar los datos a la tabla **/
    public void addData() {

        int limite = tablaPosiciones.length;
        for (int i=0; i<limite; i++) {
            adapter.addItem(tablaPosiciones[i][0], tablaPosiciones[i][1], tablaPosiciones[i][2], tablaPosiciones[i][3], tablaPosiciones[i][4],
                    tablaPosiciones[i][5], tablaPosiciones[i][6], tablaPosiciones[i][7], tablaPosiciones[i][8], tablaPosiciones[i][9], tablaPosiciones[i][10]);
        }
        ListView lview = (ListView) rootView.findViewById(R.id.listviewTablaPosIndividual);
        lview.setOnItemClickListener(new AdapterView.OnItemClickListener() {

            @Override
            public void onItemClick(AdapterView<?> parent, View view,
                                    int position, long id) {

                if(((TextView)view.findViewById(R.id.id_equipo)).getText().toString() != "") {
                    //Creamos el Intent
                    Intent intent = new Intent(getActivity(), EquipoActivity.class);
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
