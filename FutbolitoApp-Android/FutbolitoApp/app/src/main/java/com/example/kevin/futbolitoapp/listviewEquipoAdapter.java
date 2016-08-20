package com.example.kevin.futbolitoapp;

import android.app.Activity;
import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.TextView;

import java.util.ArrayList;
import java.util.TreeSet;

/**
 * Created by j3s on 8/20/16.
 */
public class listviewEquipoAdapter extends BaseAdapter {

    private static final int TYPE_ITEM = 0;
    private static final int TYPE_SEPARATOR = 1;

    public ArrayList<ModelEquipo> equipoList = new ArrayList<ModelEquipo>();
    Activity activity;
    private TreeSet<Integer> sectionHeader = new TreeSet<Integer>();
    private LayoutInflater mInflater;

//    public listviewEquipoAdapter(Activity activity, ArrayList<ModelEquipo> equipoList) {
//        super();
//        this.activity = activity;
//        this.equipoList = equipoList;
//    }

    public listviewEquipoAdapter(Context context) {
        mInflater = (LayoutInflater) context
                .getSystemService(Context.LAYOUT_INFLATER_SERVICE);
    }

    public void addItem(String nom_equipo, String pj_equipo, String pg_equipo, String pe_equipo, String pp_equipo, String gf_equipo, String gc_equipo, String gd_equipo, String pts_equipo) {
        ModelEquipo item;

        item = new ModelEquipo(nom_equipo, pj_equipo, pg_equipo, pe_equipo, pp_equipo, gf_equipo, gc_equipo, gd_equipo, pts_equipo);
        equipoList.add(item);
    }

    public void addSectionHeaderItem() {
        ModelEquipo item;

        item = new ModelEquipo("Equipo", "PJ", "PG", "PE", "PP", "GF", "GC", "GD", "PTS");
        equipoList.add(item);
        sectionHeader.add(equipoList.size()-1);
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
        return equipoList.size();
    }

    @Override
    public Object getItem(int position) {
        return equipoList.get(position);
    }

    @Override
    public long getItemId(int position) {
        return position;
    }

    private class ViewHolder {
        TextView nom_equipo;
        TextView pj_equipo;
        TextView pg_equipo;
        TextView pe_equipo;
        TextView pp_equipo;
        TextView gf_equipo;
        TextView gc_equipo;
        TextView gd_equipo;
        TextView pts_equipo;
    }

    @Override
    public View getView(int position, View convertView, ViewGroup parent) {

//        ViewHolder holder;
//        LayoutInflater inflater = activity.getLayoutInflater();
//
//        if (convertView == null) {
//            convertView = inflater.inflate(R.layout.listview_row_equipo, null);
//            holder = new ViewHolder();
//            holder.nom_equipo = (TextView) convertView.findViewById(R.id.nom_equipo);
//            holder.pj_equipo = (TextView) convertView.findViewById(R.id.pj_equipo);
//            holder.pg_equipo = (TextView) convertView.findViewById(R.id.pg_equipo);
//            holder.pe_equipo = (TextView) convertView.findViewById(R.id.pe_equipo);
//            holder.pp_equipo = (TextView) convertView.findViewById(R.id.pp_equipo);
//            holder.gf_equipo = (TextView) convertView.findViewById(R.id.gf_equipo);
//            holder.gc_equipo = (TextView) convertView.findViewById(R.id.gc_equipo);
//            holder.gd_equipo = (TextView) convertView.findViewById(R.id.gd_equipo);
//            holder.pts_equipo = (TextView) convertView.findViewById(R.id.pts_equipo);
//            convertView.setTag(holder);
//        } else {
//            holder = (ViewHolder) convertView.getTag();
//        }
//
//        ModelEquipo item = equipoList.get(position);
//        holder.nom_equipo.setText(item.get_nom_equipo().toString());
//        holder.pj_equipo.setText(item.get_pj_equipo().toString());
//        holder.pg_equipo.setText(item.get_pg_equipo().toString());
//        holder.pe_equipo.setText(item.get_pe_equipo().toString());
//        holder.pp_equipo.setText(item.get_pp_equipo().toString());
//        holder.gf_equipo.setText(item.get_gf_equipo().toString());
//        holder.gc_equipo.setText(item.get_gc_equipo().toString());
//        holder.gd_equipo.setText(item.get_gd_equipo().toString());
//        holder.pts_equipo.setText(item.get_pts_equipo().toString());
//
//        return convertView;

        ViewHolder holder = null;
        int rowType = getItemViewType(position);

        if (convertView == null) {
            holder = new ViewHolder();
            switch (rowType) {
                case TYPE_ITEM:
                    convertView = mInflater.inflate(R.layout.listview_row_equipo, null);
                    holder.nom_equipo = (TextView) convertView.findViewById(R.id.nom_equipo);
                    holder.pj_equipo = (TextView) convertView.findViewById(R.id.pj_equipo);
                    holder.pg_equipo = (TextView) convertView.findViewById(R.id.pg_equipo);
                    holder.pe_equipo = (TextView) convertView.findViewById(R.id.pe_equipo);
                    holder.pp_equipo = (TextView) convertView.findViewById(R.id.pp_equipo);
                    holder.gf_equipo = (TextView) convertView.findViewById(R.id.gf_equipo);
                    holder.gc_equipo = (TextView) convertView.findViewById(R.id.gc_equipo);
                    holder.gd_equipo = (TextView) convertView.findViewById(R.id.gd_equipo);
                    holder.pts_equipo = (TextView) convertView.findViewById(R.id.pts_equipo);
                    break;
                case TYPE_SEPARATOR:
                    convertView = mInflater.inflate(R.layout.listview_row_header_equipo, null);
                    holder.nom_equipo = (TextView) convertView.findViewById(R.id.nom_equipo_head);
                    holder.pj_equipo = (TextView) convertView.findViewById(R.id.pj_equipo_head);
                    holder.pg_equipo = (TextView) convertView.findViewById(R.id.pg_equipo_head);
                    holder.pe_equipo = (TextView) convertView.findViewById(R.id.pe_equipo_head);
                    holder.pp_equipo = (TextView) convertView.findViewById(R.id.pp_equipo_head);
                    holder.gf_equipo = (TextView) convertView.findViewById(R.id.gf_equipo_head);
                    holder.gc_equipo = (TextView) convertView.findViewById(R.id.gc_equipo_head);
                    holder.gd_equipo = (TextView) convertView.findViewById(R.id.gd_equipo_head);
                    holder.pts_equipo = (TextView) convertView.findViewById(R.id.pts_equipo_head);
                    break;
            }
            convertView.setTag(holder);
        } else {
            holder = (ViewHolder) convertView.getTag();
        }

        ModelEquipo item = equipoList.get(position);
        holder.nom_equipo.setText(item.get_nom_equipo().toString());
        holder.pj_equipo.setText(item.get_pj_equipo().toString());
        holder.pg_equipo.setText(item.get_pg_equipo().toString());
        holder.pe_equipo.setText(item.get_pe_equipo().toString());
        holder.pp_equipo.setText(item.get_pp_equipo().toString());
        holder.gf_equipo.setText(item.get_gf_equipo().toString());
        holder.gc_equipo.setText(item.get_gc_equipo().toString());
        holder.gd_equipo.setText(item.get_gd_equipo().toString());
        holder.pts_equipo.setText(item.get_pts_equipo().toString());

        return convertView;
    }

}
