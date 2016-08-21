package com.example.kevin.futbolitoapp;

import android.content.Context;
import android.support.v4.app.Fragment;
import android.support.v4.app.FragmentManager;
import android.support.v4.app.FragmentPagerAdapter;

/**
 * Created by j3s on 8/21/16.
 */
public class FragmentEquipoPagerAdapter extends FragmentPagerAdapter {
    final int PAGE_COUNT = 3;
    private String tabTitles[] = new String[] { "Detalles", "Clasificación", "Plantilla" };
    private Context context;
    private String id_equipo;

    public FragmentEquipoPagerAdapter(FragmentManager fm, Context context, String id_equipo) {
        super(fm);
        this.context = context;
        this.id_equipo = id_equipo;
    }

    @Override
    public int getCount() {
        return PAGE_COUNT;
    }

    @Override
    public Fragment getItem(int position) {
        System.out.println("Posicion: " + position);
        if(position == 0)
            return InfoGeneralEquipoFragment.newInstance(id_equipo);
        else if(position == 1)
            return PosicionEquipoFragment.newInstance(id_equipo);
        else
            return PlantillaEquipoFragment.newInstance(id_equipo);
    }

    @Override
    public CharSequence getPageTitle(int position) {
        // Generate title based on item position
        return tabTitles[position];
    }
}
