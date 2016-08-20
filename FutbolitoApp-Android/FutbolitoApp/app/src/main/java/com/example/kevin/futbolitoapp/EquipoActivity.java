package com.example.kevin.futbolitoapp;

import android.os.Bundle;
import android.support.v7.app.AppCompatActivity;
import android.widget.TextView;

public class EquipoActivity extends AppCompatActivity {

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_equipo);
        String s= getIntent().getStringExtra("ID");
        TextView tv = (TextView)findViewById(R.id.lbldatos);
        tv.setText(s);
    }
}
