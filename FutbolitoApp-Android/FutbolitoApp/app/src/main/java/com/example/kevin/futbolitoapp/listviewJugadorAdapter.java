package com.example.kevin.futbolitoapp;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.TextView;

import java.util.ArrayList;
import java.util.TreeSet;

/**
 * Created by j3s on 8/21/16.
 */
public class listviewJugadorAdapter extends BaseAdapter {

    private static final int TYPE_ITEM = 0;
    private static final int TYPE_SEPARATOR = 1;

    public ArrayList<ModelJugador> jugadorList = new ArrayList<ModelJugador>();
    private TreeSet<Integer> sectionHeader = new TreeSet<Integer>();
    private LayoutInflater mInflater;

    public listviewJugadorAdapter(Context context) {
        mInflater = (LayoutInflater) context
                .getSystemService(Context.LAYOUT_INFLATER_SERVICE);
    }

    public void addItem(String nom_jugador, String rol_jugador, String camis_jugador, String id) {
        ModelJugador item;

        item = new ModelJugador(nom_jugador, rol_jugador, camis_jugador, id);
        jugadorList.add(item);
    }

    public void addSectionHeaderItem(String rol) {
        ModelJugador item;
        item = new ModelJugador("", rol, "", "");
        jugadorList.add(item);
        sectionHeader.add(jugadorList.size()-1);
        notifyDataSetChanged();
    }

    @Override
    public int getItemViewType(int position) {
        return sectionHeader.contains(position) ? TYPE_SEPARATOR : TYPE_ITEM;
    }

    @Override
    public int getViewTypeCount() {
        return 2;
    }

    @Override
    public int getCount() {
        return jugadorList.size();
    }

    @Override
    public Object getItem(int position) {
        return jugadorList.get(position);
    }

    @Override
    public long getItemId(int position) {
        return position;
    }

    private class ViewHolder {
        TextView nom_jugador;
        TextView rol_jugador;
        TextView camis_jugador;
        TextView id_jugador;
        TextView rol_agrupado;
    }

    @Override
    public View getView(int position, View convertView, ViewGroup parent) {

        ViewHolder holder = null;
        int rowType = getItemViewType(position);

        if (convertView == null) {
            holder = new ViewHolder();
            switch (rowType) {
                case TYPE_ITEM:
                    convertView = mInflater.inflate(R.layout.listview_row_jugador, null);
                    holder.nom_jugador = (TextView) convertView.findViewById(R.id.nom_jugador);
                    holder.rol_jugador = (TextView) convertView.findViewById(R.id.rol_jugador);
                    holder.camis_jugador = (TextView) convertView.findViewById(R.id.camis_jugador);
                    holder.id_jugador = (TextView) convertView.findViewById(R.id.id_jugador);
                    break;
                case TYPE_SEPARATOR:
                    convertView = mInflater.inflate(R.layout.listview_row_header_jugador, null);
                    holder.rol_agrupado = (TextView) convertView.findViewById(R.id.rol_agrupado);
                    break;
            }
            convertView.setTag(holder);
        } else {
            holder = (ViewHolder) convertView.getTag();
        }
        ModelJugador item = jugadorList.get(position);
        if(rowType == TYPE_SEPARATOR) {
            holder.rol_agrupado.setText(item.get_rol_jugador().toString());
        } else {
            holder.nom_jugador.setText(item.get_nom_jugador().toString());
            holder.rol_jugador.setText(item.get_rol_jugador().toString());
            holder.camis_jugador.setText(item.get_camis_jugador().toString());
            holder.id_jugador.setText(item.get_id_jugador().toString());
        }

        return convertView;
    }
}
