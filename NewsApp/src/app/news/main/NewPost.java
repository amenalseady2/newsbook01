package app.news.main;

import android.app.Activity;
import android.os.Bundle;
import android.view.View;
import android.widget.EditText;
import android.widget.TextView;
import android.widget.Toast;

import com.news.client.RequestMethod;
import com.news.client.RestClient;



public class NewPost extends Activity {

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.newpost);
	}

	public void onClickShare(View view) {
		EditText txtShare = (EditText) findViewById(R.id.txtShare);
		TextView txtAfterPost = (TextView) findViewById(R.id.txtAfterPost);
		String shareDetail = txtShare.getText().toString();
		shareDetail = shareDetail.intern();
		
		if (shareDetail.length()==0)
			return;

		RestClient client = new RestClient(getString(R.string.AppConnection));

		client.AddParam("action", "createPost");
		client.AddParam("post", shareDetail);
		try {
			client.Execute(RequestMethod.GET);
		} catch (Exception e) {
		}

		txtAfterPost.setText(">> " + shareDetail);

		txtShare.setText("");
		Toast.makeText(getApplicationContext(),
				"Post shared successfully!", Toast.LENGTH_SHORT)
				.show();
	}

}