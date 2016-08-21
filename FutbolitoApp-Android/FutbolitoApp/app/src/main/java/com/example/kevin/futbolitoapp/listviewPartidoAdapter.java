package com.example.kevin.futbolitoapp;

import android.content.Context;
import android.graphics.Color;
import android.graphics.Typeface;
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
public class listviewPartidoAdapter extends BaseAdapter {

    private static final int TYPE_ITEM = 0;
    private static final int TYPE_SEPARATOR = 1;

    private String nombre_equipo = "";

    public ArrayList<ModelPartido> partidoList = new ArrayList<ModelPartido>();
    private TreeSet<Integer> sectionHeader = new TreeSet<Integer>();
    private LayoutInflater mInflater;


    public listviewPartidoAdapter(Context context, String nombre) {
        mInflater = (LayoutInflater) context
                .getSystemService(Context.LAYOUT_INFLATER_SERVICE);
        nombre_equipo = nombre;
    }

    public void addItem(String id_partido, String fecha_partido, String equipo_local, String equipo_visitante, String gol_local, String gol_visitante, String nombre_torneo) {
        ModelPartido item;

        item = new ModelPartido(id_partido, fecha_partido, equipo_local, equipo_visitante, gol_local, gol_visitante, nombre_torneo);
        partidoList.add(item);
    }

    public void addSectionHeaderItem(String nombre_torneo) {
        ModelPartido item;
        item = new ModelPartido("", "", "", "", "", "", nombre_torneo);
        partidoList.add(item);
        sectionHeader.add(partidoList.size()-1);
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
        return partidoList.size();
    }

    @Override
    public Object getItem(int position) {
        return partidoList.get(position);
    }

    @Override
    public long getItemId(int position) {
        return position;
    }

    private class ViewHolder {
        TextView fecha_partido;
        TextView equipo_local;
        TextView equipo_visitante;
        TextView gol_local;
        TextView gol_visitante;
        TextView nombre_torneo;
        TextView id_partido;
    }

    @Override
    public View getView(int position, View convertView, ViewGroup parent) {

        ViewHolder holder = null;
        int rowType = getItemViewType(position);

        if (convertView == null) {
            holder = new ViewHolder();
            switch (rowType) {
                case TYPE_ITEM:
                    convertView = mInflater.inflate(R.layout.listview_row_partido, null);
                    holder.fecha_partido = (TextView) convertView.findViewById(R.id.fecha_partido);
                    holder.equipo_local = (TextView) convertView.findViewById(R.id.equipo_local);
                    holder.equipo_visitante = (TextView) convertView.findViewById(R.id.equipo_visitante);
                    holder.gol_local = (TextView) convertView.findViewById(R.id.gol_local);
                    holder.gol_visitante = (TextView) convertView.findViewById(R.id.gol_visitante);
                    holder.nombre_torneo = (TextView) convertView.findViewById(R.id.nombre_torneo);
                    holder.id_partido = (TextView) convertView.findViewById(R.id.id_partido);
                    break;
                case TYPE_SEPARATOR:
                    convertView = mInflater.inflate(R.layout.listview_row_header_partido, null);
                    holder.nombre_torneo = (TextView) convertView.findViewById(R.id.nombre_torneo);
                    break;
            }
            convertView.setTag(holder);
        } else {
            holder = (ViewHolder) convertView.getTag();
        }
        ModelPartido item = partidoList.get(position);
        if(rowType == TYPE_SEPARATOR) {
            holder.nombre_torneo.setText(item.get_nombre_torneo().toString());
        } else {
            holder.fecha_partido.setText(item.get_fecha_partido().toString());
            holder.equipo_local.setText(item.get_equipo_local().toString());
            holder.gol_local.setText(item.get_gol_local().toString());
            String eqloc = "";
            eqloc = item.get_equipo_local().toString();
            if(eqloc.equals(nombre_equipo)) {
                holder.equipo_local.setTypeface(null, Typeface.BOLD);
                holder.gol_local.setTypeface(null, Typeface.BOLD);
                holder.equipo_local.setTextColor(Color.parseColor("#b71c1c"));
                holder.gol_local.setTextColor(Color.parseColor("#b71c1c"));
            } else {
                holder.equipo_local.setTypeface(null, Typeface.NORMAL);
                holder.gol_local.setTypeface(null, Typeface.NORMAL);
                holder.equipo_local.setTextColor(holder.fecha_partido.getTextColors().getDefaultColor());
                holder.gol_local.setTextColor(holder.fecha_partido.getTextColors().getDefaultColor());
            }
            holder.equipo_visitante.setText(item.get_equipo_visitante().toString());
            holder.gol_visitante.setText(item.get_gol_visitante().toString());
            String eqvis = "";
            eqvis = item.get_equipo_visitante().toString();
            if(eqvis.equals(nombre_equipo)) {
                holder.equipo_visitante.setTypeface(null, Typeface.BOLD);
                holder.gol_visitante.setTypeface(null, Typeface.BOLD);
                holder.equipo_visitante.setTextColor(Color.parseColor("#b71c1c"));
                holder.gol_visitante.setTextColor(Color.parseColor("#b71c1c"));
            } else {
                holder.equipo_visitante.setTypeface(null, Typeface.NORMAL);
                holder.gol_visitante.setTypeface(null, Typeface.NORMAL);
                holder.equipo_visitante.setTextColor(holder.fecha_partido.getTextColors().getDefaultColor());
                holder.gol_visitante.setTextColor(holder.fecha_partido.getTextColors().getDefaultColor());
            }
            holder.nombre_torneo.setText(item.get_nombre_torneo().toString());
            holder.id_partido.setText(item.get_id_partido().toString());
        }

        return convertView;
    }

}
