package app.news.main;

import android.app.ActivityGroup;
import android.content.Intent;
import android.os.Bundle;
import android.view.View;

public class SearchMain extends ActivityGroup{
	public static SearchMain group;
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		// TODO Auto-generated method stub
		super.onCreate(savedInstanceState);
		   group = this;
	        
	        View view = getLocalActivityManager().startActivity("SearchPost", 
	                new Intent(this, SearchPost.class).addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP)).getDecorView();	        
	        setContentView(view);
	    }
}
