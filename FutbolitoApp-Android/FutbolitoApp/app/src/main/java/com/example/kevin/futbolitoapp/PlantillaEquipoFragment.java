package com.example.kevin.futbolitoapp;

import android.support.v4.app.Fragment;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ListView;
import android.widget.TextView;

import java.util.ArrayList;

/**
 * Created by j3s on 8/21/16.
 */
public class PlantillaEquipoFragment extends Fragment {

    public static final String ARG_PAGE = "ARG_PAGE";

    private int mPage;

    public static PlantillaEquipoFragment newInstance(int page) {
        Bundle args = new Bundle();
        args.putInt(ARG_PAGE, page);
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
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        // Inflate the layout for this fragment
        View rootView = inflater.inflate(R.layout.fragment_plantilla_equipo, container, false);
        TextView textView = (TextView) rootView.findViewById(R.id.test1);
        textView.setText("Final Fragmento " + mPage);
        return rootView;
    }
}
