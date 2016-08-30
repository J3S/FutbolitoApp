package com.example.kevin.futbolitoapp;

import android.graphics.Color;
import android.os.AsyncTask;
import android.os.Handler;
import android.os.Message;
import android.support.v4.app.Fragment;
import android.os.Bundle;
import android.support.v4.widget.SwipeRefreshLayout;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AbsListView;
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
public class InfoGeneralEquipoFragment extends Fragment implements SwipeRefreshLayout.OnRefreshListener {

    public static final String ID_EQUIPO = "";

    private String nombre;
    private String director;
    private String categoria;
    private String id_equipo;
    private View rootView;
    private String equipo_url = "http://futbolitoapp.herokuapp.com/get_equipo/";
    private String ultimos_partidos_url = "http://futbolitoapp.herokuapp.com/get_ult10partidosequipo/";
    private String[][][] partidos;
    private String[] nombre_torneo;
    private listviewPartidoAdapter adapter;
    private SwipeRefreshLayout infoEquipoSwipeRefresh;

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
        final ListView mListView = (ListView) rootView.findViewById(R.id.listviewPartidosEquipo);
        mListView.setOnScrollListener(new AbsListView.OnScrollListener() {
            private boolean scrollEnabled;

            @Override
            public void onScrollStateChanged(AbsListView view, int scrollState) {
            }

            @Override
            public void onScroll(AbsListView view, int firstVisibleItem, int visibleItemCount, int totalItemCount) {
                int topRowVerticalPosition =
                        (mListView == null || mListView.getChildCount() == 0) ?
                                0 : mListView.getChildAt(0).getTop();

                boolean newScrollEnabled =
                        (firstVisibleItem == 0 && topRowVerticalPosition >= 0) ?
                                true : false;

                if (null != infoEquipoSwipeRefresh && scrollEnabled != newScrollEnabled) {
                    // Start refreshing....
                    infoEquipoSwipeRefresh.setEnabled(newScrollEnabled);
                    scrollEnabled = newScrollEnabled;
                }
            }
        });
        new TareaWSInfoEquipo().execute(equipo_url + id_equipo);
        new TareaWSUltimosPartidos().execute(ultimos_partidos_url + id_equipo);
        infoEquipoSwipeRefresh = (SwipeRefreshLayout)rootView.findViewById(R.id.swipeinfoequipo);
        infoEquipoSwipeRefresh.setOnRefreshListener(this);
        infoEquipoSwipeRefresh.setDistanceToTriggerSync(30);
        infoEquipoSwipeRefresh.setSize(SwipeRefreshLayout.DEFAULT);
        infoEquipoSwipeRefresh.setColorSchemeColors(Color.GRAY, Color.GREEN, Color.BLUE,
                Color.RED, Color.CYAN);
        return rootView;
    }
    Handler mHandler = new Handler() {
        @Override
        public void handleMessage(Message msg) {
            new TareaWSInfoEquipo().execute(equipo_url + id_equipo);
            new TareaWSUltimosPartidos().execute(ultimos_partidos_url + id_equipo);
            infoEquipoSwipeRefresh.postDelayed(new Runnable() {
                @Override
                public void run() {
                    infoEquipoSwipeRefresh.setRefreshing(false);
                }
            }, 1000);
        }
    };
    @Override
    public void onRefresh() {
        infoEquipoSwipeRefresh.postDelayed(new Runnable() {
            @Override
            public void run() {
                infoEquipoSwipeRefresh.setRefreshing(true);
                mHandler.sendEmptyMessage(0);
            }
        }, 1000);
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
                if(director == "null")
                    director = "No tiene";
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
                textView.setText(nombre);
                textView = (TextView) rootView.findViewById(R.id.director_equipo);
                textView.setText(director);
                textView = (TextView) rootView.findViewById(R.id.categoria_equipo);
                textView.setText(categoria);
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

                JSONArray respJSON = new JSONArray(buffer.toString());
                partidos = new String[respJSON.length()][][];
                nombre_torneo = new String[respJSON.length()];
                for(int i=0; i<respJSON.length(); i++)
                {
                    JSONObject obj = respJSON.getJSONObject(i);
                    nombre_torneo[i] = obj.getString("nombre_torneo");
                    JSONArray partidosArray = obj.getJSONArray("partidos");
                    partidos[i] = new String[partidosArray.length()][];

                    for(int j=0; j<partidosArray.length(); j++) {
                        JSONObject objInfo = partidosArray.getJSONObject(j);
                        String id_partido = objInfo.getString("id");
                        String fecha_partido = objInfo.getString("fecha");
                        String equipo_local = objInfo.getString("equipo_local");
                        String equipo_visitante = objInfo.getString("equipo_visitante");
                        String gol_local = objInfo.getString("gol_local");
                        String gol_visitante = objInfo.getString("gol_visitante");
                        partidos[i][j] = new String[objInfo.length()];
                        partidos[i][j][0] = id_partido;
                        partidos[i][j][1] = fecha_partido;
                        partidos[i][j][2] = equipo_local;
                        partidos[i][j][3] = equipo_visitante;
                        partidos[i][j][4] = gol_local;
                        partidos[i][j][5] = gol_visitante;
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
                for (int i=0; i<partidos.length;i++) {
                    addHeaders(i);
                    addData(i);
                }
                adapter.notifyDataSetChanged();
            }
        }
    }
    public void inicio_tabla() {
        ListView lview = (ListView) rootView.findViewById(R.id.listviewPartidosEquipo);
        adapter = new listviewPartidoAdapter(rootView.getContext(), nombre);
        lview.setAdapter(adapter);
    }

    public void addHeaders(int index){
        adapter.addSectionHeaderItem(nombre_torneo[index]);
    }

    /** Agregar los datos a la tabla **/
    public void addData(int index) {

        int limite = partidos[index].length;
        for (int i=0; i<limite; i++) {
            adapter.addItem(partidos[index][i][0], partidos[index][i][1], partidos[index][i][2], partidos[index][i][3], partidos[index][i][4],
                    partidos[index][i][5], nombre_torneo[index]);
        }
        ListView lview = (ListView) rootView.findViewById(R.id.listviewPartidosEquipo);
//        lview.setOnItemClickListener(new AdapterView.OnItemClickListener() {
//
//            @Override
//            public void onItemClick(AdapterView<?> parent, View view,
//                                    int position, long id) {
//
//                if(((TextView)view.findViewById(R.id.id_equipo)).getText().toString() != "") {
//                    //Creamos el Intent
//                    Intent intent = new Intent(getActivity(), EquipoActivity.class);
//                    //Creamos la información a pasar entre actividades
//                    Bundle b = new Bundle();
//                    b.putString("ID", ((TextView) view.findViewById(R.id.id_equipo)).getText().toString());
//                    //Añadimos la información al intent
//                    intent.putExtras(b);
//                    //Iniciamos la nueva actividad
//                    startActivity(intent);
//                }
//            }
//        });
    }
}



