package app.news.main;

import android.app.AlertDialog;
import android.app.TabActivity;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.SharedPreferences;
import android.content.SharedPreferences.Editor;
import android.content.res.Resources;
import android.graphics.Typeface;
import android.os.Bundle;
import android.preference.PreferenceManager;
import android.view.KeyEvent;
import android.view.View;
import android.view.ViewGroup.LayoutParams;
import android.widget.TabHost;
import android.widget.TabHost.OnTabChangeListener;
import android.widget.TextView;
import android.widget.Toast;

public class TheMainActivity extends TabActivity {
	public static TheMainActivity tabContext;
	private TabHost tabHost;
	SharedPreferences myprefs;
	public static String isFirstTime;
	Editor updater;

	@Override
	public void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);

		setContentView(R.layout.tab_main);
		tabContext = this;

		Resources res = getResources(); // Resource object to get Drawables
		TabHost tabHost = getTabHost(); // The activity TabHost
		TabHost.TabSpec spec; // Resusable TabSpec for each tab

		// Initialize a TabSpec for each tab and add it to the TabHost
		spec = tabHost
				.newTabSpec("News")
				.setIndicator("News")//, res.getDrawable(R.drawable.tab_newpost))
				.setContent(
						new Intent(this, News.class)
								.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP));

		tabHost.addTab(spec);

		spec =   tabHost
				.newTabSpec("viewPost")
				.setIndicator("Favorites")//,res.getDrawable(R.drawable.tab_viewpost))
				.setContent(
						new Intent(this, ChannelsPref.class)
								.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP));

		tabHost.addTab(spec);

		spec = tabHost
				.newTabSpec("SearchMain")
				.setIndicator("About")//, res.getDrawable(R.drawable.tab_search))
				.setContent(
						new Intent(this, About.class)
								.setFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP));

		tabHost.addTab(spec);
		// tabHost.setCurrentTab(1);

		myprefs = PreferenceManager.getDefaultSharedPreferences(this);

		isFirstTime = myprefs.getString("isFirstTime", null);
		updater = myprefs.edit();
		if (isFirstTime != null) {
			if (isFirstTime.equalsIgnoreCase("Yes")) {
				updater.putString("isFirstTime", "No");
				updater.commit();
			}
			tabHost.setCurrentTab(0);

		} else {
			updater.putString("isFirstTime", "Yes");
			updater.commit();
			
			tabHost.setCurrentTab(1);
			
//			DatabaseHelper dbHelper;
//			dbHelper = new DatabaseHelper(tabContext);
//			dbHelper.openDB();			
//			dbHelper.close();
		}
		final float scale = getResources().getDisplayMetrics().density;
		int pixels = (int) (35 * scale + 0.5f);
		
		  tabHost.getTabWidget().getChildAt(0).getLayoutParams().height = pixels;		  
		  tabHost.getTabWidget().getChildAt(1).getLayoutParams().height = pixels;		  
		  tabHost.getTabWidget().getChildAt(2).getLayoutParams().height = pixels;
		  
		  

		  tabHost.getTabWidget().setStripEnabled(false);

		 
		  tabHost.setOnTabChangedListener(new OnTabChangeListener() {

			@Override
			public void onTabChanged(String tabId) {
				if (getTabHost().getCurrentTabTag().equalsIgnoreCase("News")) {
					DatabaseHelper dbHelpter = new DatabaseHelper(tabContext);					
					if (dbHelpter.isChannelsEmpty()) {
						Toast.makeText(
								getApplicationContext(),
								"Please select channels from Preferences",
								Toast.LENGTH_LONG).show();
					}
				}
			}
		});
	}	
}