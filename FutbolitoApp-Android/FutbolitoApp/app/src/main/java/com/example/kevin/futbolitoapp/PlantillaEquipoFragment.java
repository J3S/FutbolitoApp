package com.example.kevin.futbolitoapp;

import android.content.Intent;
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
import android.widget.AdapterView;
import android.widget.ListView;
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
import java.util.ArrayList;

/**
 * Created by j3s on 8/21/16.
 */
public class PlantillaEquipoFragment extends Fragment implements SwipeRefreshLayout.OnRefreshListener {

    public static final String ID_EQUIPO = "";
    private String jugadores_url = "http://futbolitoapp.herokuapp.com/get_jugadores_equipo/";
    private String id_equipo;
    private View rootView;
    private String[][] infoJugador;
    private String[] roles;
    private SwipeRefreshLayout plantillaSwipeRefresh;

    private listviewJugadorAdapter adapter;

    public static PlantillaEquipoFragment newInstance(String id) {
        Bundle args = new Bundle();
        args.putString(ID_EQUIPO, id);
        PlantillaEquipoFragment fragment = new PlantillaEquipoFragment();
        fragment.setArguments(args);
        return fragment;
    }

    public PlantillaEquipoFragment() {
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
        rootView = inflater.inflate(R.layout.fragment_plantilla_equipo, container, false);
        final ListView mListView = (ListView) rootView.findViewById(R.id.listviewTablaJugadoresEquipo);
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

                if (null != plantillaSwipeRefresh && scrollEnabled != newScrollEnabled) {
                    // Start refreshing....
                    plantillaSwipeRefresh.setEnabled(newScrollEnabled);
                    scrollEnabled = newScrollEnabled;
                }
            }
        });
        new TareaWSListarJugadores().execute(jugadores_url + id_equipo);
        plantillaSwipeRefresh = (SwipeRefreshLayout)rootView.findViewById(R.id.swipeplantilla);
        plantillaSwipeRefresh.setOnRefreshListener(this);
        plantillaSwipeRefresh.setDistanceToTriggerSync(30);
        plantillaSwipeRefresh.setSize(SwipeRefreshLayout.DEFAULT);
        plantillaSwipeRefresh.setColorSchemeColors(Color.GRAY, Color.GREEN, Color.BLUE,
                Color.RED, Color.CYAN);
        return rootView;
    }
    Handler mHandler = new Handler() {
        @Override
        public void handleMessage(Message msg) {
            new TareaWSListarJugadores().execute(jugadores_url + id_equipo);
            plantillaSwipeRefresh.postDelayed(new Runnable() {
                @Override
                public void run() {
                    plantillaSwipeRefresh.setRefreshing(false);
                }
            }, 1000);
        }
    };
    @Override
    public void onRefresh() {
        plantillaSwipeRefresh.postDelayed(new Runnable() {
            @Override
            public void run() {
                plantillaSwipeRefresh.setRefreshing(true);
                mHandler.sendEmptyMessage(0);
            }
        }, 1000);
    }
    //Tarea Asincrona para llamar al WS de listado de torneos en segundo plano
    private class TareaWSListarJugadores extends AsyncTask<String, Integer, Boolean> {

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
                infoJugador = new String[respJSON.length()][];
                String rol = "";
                roles = new String[respJSON.length()];
                int j = 0;
                for(int i=0; i<respJSON.length(); i++)
                {
                    JSONObject obj = respJSON.getJSONObject(i);
                    infoJugador[i] = new String[obj.length()];
                    infoJugador[i][0] = String.valueOf(obj.getInt("id"));
                    infoJugador[i][1] = obj.getString("nombre");
                    infoJugador[i][2] = obj.getString("apellido");
                    infoJugador[i][3] = obj.getString("rol");
                    if (infoJugador[i][3].length() == 0)
                        infoJugador[i][3] = "N/A";
                    infoJugador[i][4] = obj.getString("camiseta");
                    if(infoJugador[i][4] == "null")
                        infoJugador[i][4] = "N/A";

                    if(rol.equals("")){
                        rol = obj.getString("rol");
                        roles[j] = rol;
                        j++;
                    }
                    if(!rol.equals(obj.getString("rol"))){
                        roles[j] = rol;
                        j++;
                        rol = obj.getString("rol");
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
                addData();
                adapter.notifyDataSetChanged();
            }
        }
    }

    public void inicio_tabla() {
        ListView lview = (ListView) rootView.findViewById(R.id.listviewTablaJugadoresEquipo);
        adapter = new listviewJugadorAdapter(rootView.getContext());
        lview.setAdapter(adapter);
    }

    /** Agregar los datos a la tabla **/
    public void addData() {
        for (int i=0; i<infoJugador.length;i++) {
            adapter.addItem(infoJugador[i][1] + " " + infoJugador[i][2], infoJugador[i][3], infoJugador[i][4], infoJugador[i][0]);
        }
        ListView lview = (ListView) rootView.findViewById(R.id.listviewTablaJugadoresEquipo);
        lview.setOnItemClickListener(new AdapterView.OnItemClickListener() {

            @Override
            public void onItemClick(AdapterView<?> parent, View view,
                                    int position, long id) {

                if(((TextView)view.findViewById(R.id.id_jugador)).getText().toString() != "") {
                    //Creamos el Intent
                    Intent intent = new Intent(((EquipoActivity) getActivity()), JugadorActivity.class);
                    //Creamos la información a pasar entre actividades
                    Bundle b = new Bundle();
                    String ttt = ((TextView) view.findViewById(R.id.id_jugador)).getText().toString();
                    b.putString("ID_J", ((TextView) view.findViewById(R.id.id_jugador)).getText().toString());
                    //Añadimos la información al intent
                    intent.putExtras(b);
                    //Iniciamos la nueva actividad
                    startActivity(intent);
                }
            }
        });
    }
}
