package game.road.runner;

import android.app.Activity;
import android.app.AlertDialog;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.os.Bundle;
import android.view.View;
import android.view.View.OnClickListener;
import android.widget.Button;

public class WelcomeScreen extends Activity {
	public Context WelcomeScreenContext;
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		// TODO Auto-generated method stub
		super.onCreate(savedInstanceState);
		setContentView(R.layout.welcome_screen);
		WelcomeScreenContext = this;
		Button btnStart = (Button) findViewById(R.id.btnStart);	
		Button btnAboutUs = (Button) findViewById(R.id.btnAboutUs);
		
		btnStart.setOnClickListener(new OnClickListener() {
			
			@Override
			public void onClick(View v) {
				Intent intent = new Intent(WelcomeScreenContext, Roadrunner.class);
				intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);				
				startActivity(intent);			
			}
		});
	}
	
//	@Override
//	public void onBackPressed() {
//	    AlertDialog.Builder builder = new AlertDialog.Builder(this);
//	    builder.setMessage("Are you sure you want to exit?")
//	           .setCancelable(false)
//	           .setPositiveButton("Yes", new DialogInterface.OnClickListener() {
//	               public void onClick(DialogInterface dialog, int id) {
//	            	   WelcomeScreen.this.finish();
//	               }
//	           })
//	           .setNegativeButton("No", null);
////	           .setNegativeButton("No", new DialogInterface.OnClickListener() {
////	               public void onClick(DialogInterface dialog, int id) {
////	                    dialog.cancel();
////	               }
////	           });
//	    AlertDialog alert = builder.create();
//	    alert.show();
//
//	}
}
