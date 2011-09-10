package app.news.main;

import java.util.ArrayList;

import android.app.Dialog;
import android.app.ListActivity;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.Intent;
import android.database.Cursor;
import android.os.AsyncTask;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AbsListView;
import android.widget.AbsListView.OnScrollListener;
import android.widget.AdapterView;
import android.widget.AdapterView.OnItemClickListener;
import android.widget.ArrayAdapter;
import android.widget.ImageView;
import android.widget.ListView;
import android.widget.TextView;

import com.news.entities.Channel;

/*
 * It shows the List of channels
 */
public class Channels extends ListActivity {
	private ProgressDialog myProgressDialog = null;
	private ArrayList<Channel> m_channels = null;
	public PostAdapter m_adapter;
	public final Context context = this;
	public static Channels ChannelContext;
	DatabaseHelper dbHelper;
	private static String recID = "0";
	/*
	 * some private variables to implement endless scrolling
	 */
	private int visibleThreshold = 3;
	private int previousTotal = 0;
	private boolean loading = true;

	@Override
	protected void onDestroy() {
		super.onDestroy();
		if (dbHelper != null) {
			dbHelper.close();
		}
	}

	@Override
	public void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);

		setContentView(R.layout.tt_main);
		recID = "0";

		m_channels = new ArrayList<Channel>();
		this.m_adapter = new PostAdapter(this, R.layout.row, m_channels);
		setListAdapter(this.m_adapter);

		ListView lv = getListView();
		lv.setTextFilterEnabled(true);

		/*
		 * It opens the selected item in a detailed View
		 */
		lv.setOnItemClickListener(new OnItemClickListener() {
			public void onItemClick(AdapterView<?> parent, View view,
					int position, long id) {

				Channel selectedItem = (Channel) parent
						.getItemAtPosition(position);
				try {
					Intent intent = new Intent(context, RSSFeedList.class);

					intent.putExtra("id", selectedItem.getId());
					intent.putExtra("name", selectedItem.getName().toString());
					intent.putExtra("RssLink", selectedItem.getRssLink()
							.toString());
					intent.putExtra("flag", selectedItem.getFlag());

					intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);

					startActivity(intent);

					/*
					 * Intent intent = new Intent(getApplicationContext(),
					 * RSSFeedList.class); intent.putExtra("id",
					 * selectedItem.getId()); intent.putExtra("name",
					 * selectedItem.getName().toString());
					 * intent.putExtra("RssLink", selectedItem.getRssLink()
					 * .toString()); intent.putExtra("flag",
					 * selectedItem.getFlag());
					 * 
					 * intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
					 * 
					 * View newView = News.group.getLocalActivityManager()
					 * .startActivity("RSSFeedList1", intent) .getDecorView();
					 * 
					 * News.group.setContentView(newView);
					 */

				} catch (Exception e) {
					e.printStackTrace();
				}

				// Intent launchViewPostInDetail = new Intent(context,
				// RSSFeedItem.class);
				//
				// launchViewPostInDetail.putExtra("name",
				// selectedItem.getName()
				// .toString());
				//
				// startActivity(launchViewPostInDetail);
			}
		});

		lv.setOnScrollListener(new OnScrollListener() {

			@Override
			public void onScrollStateChanged(AbsListView view, int scrollState) {
			}

			@Override
			public void onScroll(AbsListView view, int firstVisibleItem,
					int visibleItemCount, int totalItemCount) {
				dbHelper = new DatabaseHelper(context);
				if (loading) {
					// if trying to loading
					if (totalItemCount > previousTotal) {

						loading = false;
						previousTotal = totalItemCount;
					}
				} else if (!loading
						&& ((totalItemCount - visibleItemCount) <= (firstVisibleItem + visibleThreshold))
						&& dbHelper.getSelectedChannelsCount() >= 12) {
					new LoadMoreEntries().execute(null, null, null);
					loading = true;
				}
			}
		});

		new LoadMoreEntries().execute(null, null, null); // loads the entries
															// for the first
															// time

	}

	/*
	 * It notifies the UI Thread to make changes to the Adapter List.
	 */
	private void notifyChange() {
		if (m_channels != null && m_channels.size() > 0) {
			m_adapter.notifyDataSetChanged();
			for (int i = 0; i < m_channels.size(); i++)
				m_adapter.add(m_channels.get(i));
		}
		// else if(m_adapter.getCount()==0 && TheMainActivity.isFirstTime !=
		// "Yes"){
		// Toast.makeText(Channels.this,
		// "Please select your favorite channels from Preferences!",
		// Toast.LENGTH_SHORT).show();
		// }
		// else if(m_adapter.getCount()!=0){
		// Toast.makeText(Channels.this, "No more news here for now",
		// Toast.LENGTH_SHORT).show();
		// }
		m_adapter.notifyDataSetChanged();
		m_channels = null;
	}

	/*
	 * It Performs a full routine of records fetching in a particular format.
	 * Then parse that format to extract information and full the m_posts
	 * ArrayList
	 */
	// private void startFetchingChannels() {
	// String[] unitChannel;
	// Channel o1 = new Channel();
	// try {
	// // TODO Instead of real method here i'm using a temperary hard code
	// method
	//
	// //channelsList = getChannels(this.m_adapter.getItemsCount());
	// channelsList = getPosts(Channels.actionType);
	// if (channelsList == null || channelsList.length == 0)
	// return;
	//
	// m_channels = new ArrayList<Channel>();
	//
	// } catch (Exception e) {
	// e.printStackTrace();
	// }
	// /*
	// * Record Format name|rssLink
	// */
	// for (int index = 0; index < channelsList.length; index++) {
	//
	// unitChannel = channelsList[index].split("\\|");
	//
	// if (unitChannel.length == 3) {
	//
	// o1 = new Channel();
	//
	// o1.setName(unitChannel[0].toString());
	// o1.setRssLink(unitChannel[1].toString());
	// o1.setFlag(Integer.parseInt(unitChannel[2].toString()));
	// m_channels.add(o1);
	// }
	// }
	// }

	private void startFetchingChannels() {
		Channel o1 = new Channel();
		try {
			m_channels = new ArrayList<Channel>();
			dbHelper = new DatabaseHelper(context);
			Cursor cur = dbHelper.getChannelsBlock_Feed(recID);
			while (cur.moveToNext()) {
				o1 = new Channel();

				o1.setId((cur.getInt((cur.getColumnIndex("_id")))));
				o1.setName(cur.getString(cur.getColumnIndex("ChannelName")));
				o1.setRssLink((cur.getString(cur.getColumnIndex("RssLink"))));
				o1.setFlag(cur.getInt(cur.getColumnIndex("flag")));
				m_channels.add(o1);
				recID = String.valueOf(o1.getId());
			}

		} catch (Exception e) {
			e.printStackTrace();
		}
	}

	private class PostAdapter extends ArrayAdapter<Channel> {

		private ArrayList<Channel> items;

		public PostAdapter(Context context, int textViewResourceId,
				ArrayList<Channel> items) {
			super(context, textViewResourceId, items);
			this.items = items;
		}

		@Override
		public View getView(int position, View convertView, ViewGroup parent) {
			View v = convertView;
			if (v == null) {
				LayoutInflater vi = (LayoutInflater) getSystemService(Context.LAYOUT_INFLATER_SERVICE);
				v = vi.inflate(R.layout.row, null);
			}
			Channel o = items.get(position);
			if (o != null) {
				TextView tt = (TextView) v.findViewById(R.id.toptext);
				tt.setTextSize(17);
				tt.setLines(1);

				if (tt != null) {
					tt.setText(o.getName());
				}
				ImageView img = (ImageView) v.findViewById(R.id.icon);
				
				if (tt.getText().toString().contains("Aaj Tv")) {
					img.setImageDrawable(getResources().getDrawable(R.drawable.aaj_tv));
				}
				else if (tt.getText().toString().contains("The News")) {
					img.setImageDrawable(getResources().getDrawable(R.drawable.the_news));
					
				}
				else if (tt.getText().toString().contains("CNN")) {
					img.setImageDrawable(getResources().getDrawable(R.drawable.cnn));
				}
				else if (tt.getText().toString().contains("Nation")) {
					img.setImageDrawable(getResources().getDrawable(R.drawable.the_nation));
				}
				else if (tt.getText().toString().contains("BBC")) {
					img.setImageDrawable(getResources().getDrawable(R.drawable.bbc));
				}
				else if (tt.getText().toString().contains("Fox News")) {
					img.setImageDrawable(getResources().getDrawable(R.drawable.fox_news_logo));
				}
				else if (tt.getText().toString().contains("New York Times")) {
					img.setImageDrawable(getResources().getDrawable(R.drawable.new_york_times));
				}
				else if (tt.getText().toString().contains("BigNewsNetwork")) {
					img.setImageDrawable(getResources().getDrawable(R.drawable.big_news_net));
				}
				else if (tt.getText().toString().contains("CBC -")) {
					img.setImageDrawable(getResources().getDrawable(R.drawable.cbc_news));
				}
				else if (tt.getText().toString().contains("OnePak")) {
					img.setImageDrawable(getResources().getDrawable(R.drawable.one_pakistan));
				}
			}
			return v;
		}

		public int getItemsCount() {
			return this.items.size();
		}
	}

	@Override
	protected Dialog onCreateDialog(int id) {
		myProgressDialog = ProgressDialog.show(News.group, "",
				"Loading.Please wait...", false);

		return myProgressDialog;
	}

	private class LoadMoreEntries extends AsyncTask<Void, Void, Void> {

		@Override
		protected void onPreExecute() {
			super.onPreExecute();
			showDialog(1);
		}

		@Override
		protected Void doInBackground(Void... params) {
			startFetchingChannels();
			return null;
		}

		@Override
		protected void onPostExecute(Void result) {
			super.onPostExecute(result);
			myProgressDialog.dismiss();
			notifyChange();
		}
	}

}