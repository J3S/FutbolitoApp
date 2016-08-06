package com.example.kevin.futbolitoapp;

import android.content.Intent;
import android.os.AsyncTask;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.MalformedURLException;
import java.net.URL;

public class MainActivity extends AppCompatActivity {

    private TextView jsonData;
    private String base_url = "http://192.168.1.129:8000/";

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        Button btn_jugadores = (Button)findViewById(R.id.btn_jugadores);
        Button btn_equipos = (Button)findViewById(R.id.btn_equipos);
        Button btn_partidos = (Button)findViewById(R.id.btn_partidos);
        Button btn_torneos = (Button)findViewById(R.id.btn_torneos);
        Button btn_categorias = (Button)findViewById(R.id.btn_categorias);
        Button btn_torneoequipos = (Button)findViewById(R.id.btn_torneoequipos);
        Button btn_aniotorneo = (Button)findViewById(R.id.btn_aniotorneo);
        Button btn_jornada = (Button)findViewById(R.id.btn_jornada);
        Button btn_tablaposiciones = (Button)findViewById(R.id.btn_tablaposiciones);

        btn_jugadores.setOnClickListener(new View.OnClickListener(){

            @Override
            public void onClick(View view) {
                new JSONTask().execute(base_url+"get_jugadores");
                setContentView(R.layout.activity_datos);
                jsonData = (TextView)findViewById(R.id.datos);
            }
        });
        btn_equipos.setOnClickListener(new View.OnClickListener(){

            @Override
            public void onClick(View view) {
                new JSONTask().execute(base_url+"get_equipos");
                setContentView(R.layout.activity_datos);
                jsonData = (TextView)findViewById(R.id.datos);
            }
        });
        btn_torneos.setOnClickListener(new View.OnClickListener(){

            @Override
            public void onClick(View view) {
                new JSONTask().execute(base_url+"get_torneos");
                setContentView(R.layout.activity_datos);
                jsonData = (TextView)findViewById(R.id.datos);
            }
        });
        btn_partidos.setOnClickListener(new View.OnClickListener(){

            @Override
            public void onClick(View view) {
                new JSONTask().execute(base_url+"get_partidos");
                setContentView(R.layout.activity_datos);
                jsonData = (TextView)findViewById(R.id.datos);
            }
        });
        btn_categorias.setOnClickListener(new View.OnClickListener(){

            @Override
            public void onClick(View view) {
                new JSONTask().execute(base_url+"get_categorias");
                setContentView(R.layout.activity_datos);
                jsonData = (TextView)findViewById(R.id.datos);
            }
        });
        btn_torneoequipos.setOnClickListener(new View.OnClickListener(){

            @Override
            public void onClick(View view) {
                new JSONTask().execute(base_url+"get_torneoequipos");
                setContentView(R.layout.activity_datos);
                jsonData = (TextView)findViewById(R.id.datos);
            }
        });
        btn_jornada.setOnClickListener(new View.OnClickListener(){

            @Override
            public void onClick(View view) {
                new JSONTask().execute(base_url+"get_jornada/1/1");
                setContentView(R.layout.activity_datos);
                jsonData = (TextView)findViewById(R.id.datos);
            }
        });
        btn_tablaposiciones.setOnClickListener(new View.OnClickListener(){

            @Override
            public void onClick(View view) {
                new JSONTask().execute(base_url+"get_tablaposiciones/1");
                setContentView(R.layout.activity_datos);
                jsonData = (TextView)findViewById(R.id.datos);
            }
        });

        //Implementamos el evento click del botón
        btn_aniotorneo.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                //Creamos el Intent
                Intent intent =
                        new Intent(MainActivity.this, TablaPosicionesActivity.class);

                //Creamos la información a pasar entre actividades
//                Bundle b = new Bundle();
//                b.putString("NOMBRE", txtNombre.getText().toString());

                //Añadimos la información al intent
//                intent.putExtras(b);

                //Iniciamos la nueva actividad
                startActivity(intent);
            }
        });
    }

    public class JSONTask extends AsyncTask<String , String, String>{

        @Override
        protected String doInBackground(String... params) {
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

                while((line = reader.readLine()) != null){
                    buffer.append(line);
                }

                return buffer.toString();

            } catch (MalformedURLException e) {
                e.printStackTrace();
            } catch (IOException e) {
                e.printStackTrace();
            } finally{
                if(connection != null) {
                    connection.disconnect();
                }
                try {
                    if(reader != null){
                        reader.close();
                    }
                } catch (IOException e) {
                    e.printStackTrace();
                }
            }
            return null;
        }

        @Override
        protected void onPostExecute(String result) {
            super.onPostExecute(result);
            jsonData.setText(result);
        }
    }
//    @Override
//    public boolean onCreateOptionsMenu(Menu menu) {
//        // Inflate the menu; this adds items to the action bar if it is present.
//        getMenuInflater().inflate(R.menu.menu_main, menu);
//        return true;
//    }
//
//    @Override
//    public boolean onOptionsItemSelected(MenuItem item) {
//        // Handle action bar item clicks here. The action bar will
//        // automatically handle clicks on the Home/Up button, so long
//        // as you specify a parent activity in AndroidManifest.xml.
//        int id = item.getItemId();
//
//        //noinspection SimplifiableIfStatement
//        if (id == R.id.action_settings) {
//            return true;
//        }
//
//        return super.onOptionsItemSelected(item);
//    }

}
