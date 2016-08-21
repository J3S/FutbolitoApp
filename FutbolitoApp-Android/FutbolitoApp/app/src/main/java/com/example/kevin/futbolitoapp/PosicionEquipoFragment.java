package com.example.kevin.futbolitoapp;

import android.support.v4.app.Fragment;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;

/**
 * Created by j3s on 8/21/16.
 */
public class PosicionEquipoFragment extends Fragment {

    public static final String ARG_PAGE = "ARG_PAGE";

    private int mPage;

    public static PosicionEquipoFragment newInstance(int page) {
        Bundle args = new Bundle();
        args.putInt(ARG_PAGE, page);
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
        mPage = getArguments().getInt(ARG_PAGE);
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        // Inflate the layout for this fragment
        View rootView = inflater.inflate(R.layout.fragment_posicion_equipo, container, false);
        TextView textView = (TextView) rootView.findViewById(R.id.test12);
        textView.setText("Nuevo Fragmento" + mPage);
        return rootView;
    }
}
