package app.news.main;

import android.app.Activity;
import android.database.Cursor;
import android.os.Bundle;
import android.view.View;
import android.widget.Toast;



public class SearchPost extends Activity {
	DatabaseHelper dbHelper;
	
	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.searchpost);
	}

	public void onClickSearch(View view) {	
		
		dbHelper=new DatabaseHelper(this);
		Toast.makeText(SearchPost.this, "ChannelCount = "+String.valueOf(dbHelper.getEmployeeCount()),
				Toast.LENGTH_LONG).show();
		
		Cursor cur =dbHelper.getAllEmployees();
		String chnlName="";
		while(cur.moveToNext()){
			chnlName = cur.getString(1);
			Toast.makeText(SearchPost.this, "ChannelCount = "+String.valueOf(cur.getPosition()) + " " +chnlName,
					Toast.LENGTH_SHORT).show();
				
		}
		
		//txtViewSearch
		//txtEmps.setText(txtEmps.getText()+String.valueOf(dbHelper.getEmployeeCount()));
		
		
		
		
		
		
		
		
		
		
//		EditText txtSearch = (EditText) findViewById(R.id.txtSearch);
//		String shareDetail = txtSearch.getText().toString();
//
//		if (shareDetail.length()==0)
//			return;
//
//		Intent intent = new Intent(getApplicationContext(), SearchResult.class);
//        intent.putExtra("txtSearch", shareDetail);
//        intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
//        
//        View newView = SearchMain.group.getLocalActivityManager().startActivity("SearchResult", intent).getDecorView();
//        
//        SearchMain.group.setContentView(newView);
        

//		
//		
//		RestClient client = new RestClient(getString(R.string.AppConnection));
//
//		client.AddParam("action", "searchPost");
//		client.AddParam("post", shareDetail);
//		try {
//			client.Execute(RequestMethod.GET);
//		} catch (Exception e) {
//		}
//		txtSearch.setText("");
	}
}