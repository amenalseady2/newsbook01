package app.news.main;

import android.app.ActivityGroup;
import android.content.Intent;
import android.os.Bundle;
import android.view.View;

public class News extends ActivityGroup{
	public static News group;
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		   group = this;
	        try{
	        View view = getLocalActivityManager().startActivity("Chnls", 
	                //new Intent(this, Channels.class).addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP)).getDecorView();	        
	        		new Intent(this, Channels.class).addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP)).getDecorView();
	        setContentView(view);
	        }catch (Exception e) {
	        	e.printStackTrace();
	        }
	    }
}