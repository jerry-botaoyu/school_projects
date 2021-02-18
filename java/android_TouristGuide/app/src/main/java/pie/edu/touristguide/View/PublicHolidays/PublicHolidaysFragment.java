package pie.edu.touristguide.View.PublicHolidays;

import android.os.Bundle;
import android.os.StrictMode;
import android.support.annotation.NonNull;
import android.support.annotation.Nullable;
import android.support.design.widget.FloatingActionButton;
import android.support.v4.app.Fragment;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;

import net.yslibrary.android.keyboardvisibilityevent.KeyboardVisibilityEvent;
import net.yslibrary.android.keyboardvisibilityevent.KeyboardVisibilityEventListener;

import java.util.ArrayList;
import java.util.Calendar;

import pie.edu.touristguide.Controller.APICall.GetPublicHolidayTask;
import pie.edu.touristguide.Model.PublicHoliday;
import pie.edu.touristguide.R;
import pie.edu.touristguide.View.Weather.WeatherDialog;

/**
 * @author Sebastien El-Hamaoui
 * Displays the Public Holidays for a specified Country in a RecyclerView.
 */

public class PublicHolidaysFragment extends Fragment {
    //Variables declaration.
    private ArrayList<PublicHoliday> publicHolidaysList;
    public static PublicHolidaysRVAdapter adapter;
    public static String currentYear = "";
    View rootView;

    public PublicHolidaysFragment() {
        //Empty Constructor.
    }

    //Returns the view with all the UI elements and calls the API to fetch the data.
    @Nullable
    @Override
    public View onCreateView(@NonNull LayoutInflater inflater, @Nullable ViewGroup container,
                             @Nullable Bundle savedInstanceState) {
        rootView = inflater.inflate(R.layout.public_holidays_layout, container, false);

        //Floating action button will be used to open the PublicHolidaysDialog fragment.
        FloatingActionButton floatingActionButton = rootView.findViewById(R.id.fab_set_country);
        floatingActionButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                openDialog();
            }
        });

        //Soft keyboard listener that will refresh the recycler view when the keyboard closes.
        KeyboardVisibilityEvent.setEventListener(getActivity(), new KeyboardVisibilityEventListener() {
            @Override
            public void onVisibilityChanged(boolean isOpen) {
                if(!isOpen) {
                    PublicHolidaysFragment.adapter.notifyDataSetChanged(); //Update RecyclerView.
                }
            }
        });

        //Gets the current year for the API call.
        int currentYearInt = Calendar.getInstance().get(Calendar.YEAR);
        currentYear = Integer.toString(currentYearInt);

        try {
            //Used to ignore the android restrictions on internet access.
            int SDK_INT = android.os.Build.VERSION.SDK_INT;
            if (SDK_INT > 8) {
                StrictMode.ThreadPolicy policy = new StrictMode.ThreadPolicy.Builder()
                        .permitAll().build();
                StrictMode.setThreadPolicy(policy);

                publicHolidaysList = GetPublicHolidayTask.getPublicHolidayObject();
            }

            //Declaring the Recycler view.
            RecyclerView recyclerView = rootView.findViewById(R.id.rv_holidays);
            recyclerView.setLayoutManager(new LinearLayoutManager(getActivity()));
            adapter = new PublicHolidaysRVAdapter(publicHolidaysList, getActivity());
            recyclerView.setAdapter(adapter);
        }
        catch (Exception e) {
            e.printStackTrace();
        }

        return rootView;
    }

    //Opens the PublicHolidaysDialog fragment to receive the user input.
    public void openDialog() {
        PublicHolidaysDialog publicHolidaysDialog = new PublicHolidaysDialog();
        publicHolidaysDialog.show(getFragmentManager(), "Search Country Holidays");
    }
}
