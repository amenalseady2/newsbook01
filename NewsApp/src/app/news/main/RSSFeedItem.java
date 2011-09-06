package app.news.main;

import java.io.IOException;
import java.net.MalformedURLException;

import android.app.Activity;
import android.os.Bundle;
import android.os.Handler;
import android.util.Log;
import android.view.View;
import android.widget.ImageButton;
import android.widget.TextView;
import android.widget.Toast;

import com.ecs.android.facebook.Sample.FacebookConnector;
import com.ecs.android.facebook.Sample.SessionEvents;



public class RSSFeedItem extends Activity {

	private static final String FACEBOOK_APPID = "188800691170305";
	private static final String FACEBOOK_PERMISSION = "publish_stream";
	private static final String TAG = "FacebookSample";
	private static final String MSG = "Message from FacebookSample";
	private String name;
	private String postDetail;
	private String postHeading;
	
	private final Handler mFacebookHandler = new Handler();
	private TextView loginStatus;
	private FacebookConnector facebookConnector;
	
    final Runnable mUpdateFacebookNotification = new Runnable() {
        public void run() {
        	Toast.makeText(getApplicationContext(), "Facebook wall updated !", Toast.LENGTH_LONG).show();
        }
    };
	
	@Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.detailpost_main);
		Bundle extras = getIntent().getExtras();
		if (extras != null) {
		postDetail = extras.getString("postDetail");
		postHeading =  extras.getString("postHeading");
		}

		TextView txtPostDetail = (TextView) findViewById(R.id.txtdetail);
		TextView txtPostHeading = (TextView) findViewById(R.id.txtHeading);
		txtPostHeading.setText(postHeading);
		//postDetail =removeImgUrl(postDetail);
		txtPostDetail.setText(postDetail);
        
        this.facebookConnector = new FacebookConnector(FACEBOOK_APPID, this, getApplicationContext(), new String[] {FACEBOOK_PERMISSION});
        
        ImageButton btnfbshare = (ImageButton) findViewById(R.id.btnfbshare);
       
        btnfbshare.setBackgroundDrawable(getResources().getDrawable(R.drawable.facebook));
        
       // loginStatus = (TextView)findViewById(R.id.login_status);
       // Button tweet = (Button) findViewById(R.id.btn_post);
       // Button clearCredentials = (Button) findViewById(R.id.btn_clear_credentials);
        //btnfbshare.VISIBLE=
        btnfbshare.setVisibility(btnfbshare.GONE);
        btnfbshare.setOnClickListener(new View.OnClickListener() {
        	/**
        	 * Send a tweet. If the user hasn't authenticated to Tweeter yet, he'll be redirected via a browser
        	 * to the twitter login page. Once the user authenticated, he'll authorize the Android application to send
        	 * tweets on the users behalf.
        	 */
            public void onClick(View v) {
        		postMessage();
            }
        });

//        clearCredentials.setOnClickListener(new View.OnClickListener() {
//            public void onClick(View v) {
//            	clearCredentials();
//            	updateLoginStatus();
//            }
//        });
       
	}

	private String removeImgUrl(String postDetail){
	int index1,index2,index3;
		if(postDetail.contains("<img src")){
			index1 = postDetail.indexOf("<img src");
		postDetail = postDetail.substring(index1, postDetail.length()).substring(postDetail.indexOf(">")+1, postDetail.length());
		
		}
		return postDetail;
	}
	
	//	
//	@Override
//	protected void onActivityResult(int requestCode, int resultCode, Intent data) {
//		this.facebookConnector.getFacebook().authorizeCallback(requestCode, resultCode, data);
//	}
//	
//	
//	@Override
//	protected void onResume() {
//		super.onResume();
//		updateLoginStatus();
//	}
	
	public void updateLoginStatus() {
		loginStatus.setText("Logged into Twitter : " + facebookConnector.getFacebook().isSessionValid());
	}	

	private String getFacebookMsg() {
		String txtDetail=null;
		if(postDetail.length()>=415){
			txtDetail = postDetail.substring(0, 415) + "...";
		}
		return txtDetail;
	}	
	
	public void postMessage() {
		
		if (facebookConnector.getFacebook().isSessionValid()) {
			postMessageInThread();
		} else {
			SessionEvents.AuthListener listener = new SessionEvents.AuthListener() {
				
				@Override
				public void onAuthSucceed() {
					postMessageInThread();
				}
				
				@Override
				public void onAuthFail(String error) {
					
				}
			};
			SessionEvents.addAuthListener(listener);
			facebookConnector.login();
		}
	}

	private void postMessageInThread() {
		Thread t = new Thread() {
			public void run() {
		    	
		    	try {
		    		facebookConnector.postMessageOnWall(getFacebookMsg());
					mFacebookHandler.post(mUpdateFacebookNotification);
				} catch (Exception ex) {
					Log.e(TAG, "Error sending msg",ex);
				}
		    }
		};
		t.start();
	}

	private void clearCredentials() {
		try {
			facebookConnector.getFacebook().logout(getApplicationContext());
		} catch (MalformedURLException e) {
			e.printStackTrace();
		} catch (IOException e) {
			e.printStackTrace();
		}
	}
}