package app.news.main;

import java.util.ArrayList;

import android.app.Dialog;
import android.app.ListActivity;
import android.app.ProgressDialog;
import android.content.Context;
import android.content.Intent;
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

import com.news.client.RequestMethod;
import com.news.client.RestClient;
import com.news.entities.Channel;
import com.news.entities.Post;

public class RSSFeedList extends ListActivity {
	private static String cursorString;
	private static String actionType;
	private ProgressDialog myProgressDialog = null;
	private String[] mainPosts = null;
	private ArrayList<Post> m_posts = null;
	public PostAdapter m_adapter;
	private final Context context = this;
	public static Channel selectedChannel;

	/*
	 * some private variables to implement endless scrolling
	 */
	private int visibleThreshold = 1;
	private int previousTotal = 0;
	private boolean loading = true;

	@Override
	public void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.tt_main);
		selectedChannel= new Channel();
		Intent intent = getIntent();
		selectedChannel.setId(intent.getExtras().getInt("id"));
		selectedChannel.setName(intent.getExtras().getString("name"));
		selectedChannel.setRssLink(intent.getExtras().getString("RssLink"));
		selectedChannel.setFlag(intent.getExtras().getInt("flag"));
		
		
		m_posts = new ArrayList<Post>();
		this.m_adapter = new PostAdapter(this, R.layout.row, m_posts);
		setListAdapter(this.m_adapter);

		ListView lv = getListView();
		lv.setTextFilterEnabled(true);

		
		/*
		 * It opens the selected item in a detailed View
		 */
		lv.setOnItemClickListener(new OnItemClickListener() {
			public void onItemClick(AdapterView<?> parent, View view,
					int position, long id) {

				Post selectedItem = (Post) parent.getItemAtPosition(position);

				Intent launchViewPostInDetail = new Intent(context,
						RSSFeedItem.class);

				launchViewPostInDetail.putExtra("postHeading", selectedItem
						.getPostHeading());

				launchViewPostInDetail.putExtra("postDetail", selectedItem
						.getPostDetail());

				startActivity(launchViewPostInDetail);
			}
		});

		lv.setOnScrollListener(new OnScrollListener() {

			@Override
			public void onScrollStateChanged(AbsListView view, int scrollState) {
			}

			@Override
			public void onScroll(AbsListView view, int firstVisibleItem,
					int visibleItemCount, int totalItemCount) {

				if (loading) {
					// if trying to loading
					if (totalItemCount > previousTotal) {

						loading = false;
						previousTotal = totalItemCount;
					}
				} else if (!loading
						&& ((totalItemCount - visibleItemCount) <= (firstVisibleItem + visibleThreshold))) {
					RSSFeedList.actionType = "getMoreRSS";
					new LoadMoreEntries().execute(null, null, null);
					loading = true;
				}
			}
		});
		RSSFeedList.actionType = "getRSS";
		new LoadMoreEntries().execute(null, null, null); // loads the entries
															// for the first
															// time

	}

	/*
	 * It notifies the UI Thread to make changes to the Adapter List.
	 */
	private void notifyChange() {
		if (m_posts != null && m_posts.size() > 0) {
			m_adapter.notifyDataSetChanged();
			for (int i = 0; i < m_posts.size(); i++)
				m_adapter.add(m_posts.get(i));
		} 
//		else {
//			Toast.makeText(RSSFeedList.this, "Please try again",
//					Toast.LENGTH_SHORT).show();
//		}
		m_adapter.notifyDataSetChanged();
		m_posts = null;
	}

	/*
	 * It Performs a full routine of records fetching in a particular format.
	 * Then parse that format to extract information and full the m_posts
	 * ArrayList
	 */
	private void startFetchingPosts() {
		String[] unitPost;
		Post o1 = new Post();
		try {

			mainPosts = getPosts(RSSFeedList.actionType);
			if (mainPosts == null || mainPosts.length == 0)
				return;

			m_posts = new ArrayList<Post>();

		} catch (Exception e) {
			e.printStackTrace();
		}
		/*
		 * Record Format heading|detail
		 */

		for (int index = 0; index < mainPosts.length; index++) {

			unitPost = mainPosts[index].split("\\|");

			if (unitPost.length == 2) {

				o1 = new Post();

				// o1.setId(unitPost[0].toString());
				o1.setPostHeading(unitPost[0].toString());
				o1.setPostDetail(unitPost[1].toString());
				// o1.setAgreeCount(Integer.parseInt(unitPost[2].toString()));
				// o1.setDisagreeCount(Integer.parseInt(unitPost[3].toString()));

				m_posts.add(o1);
			}
		}

	}

	/*
	 * It gets the post from the App Engine and split the result using tilda(~) 
	 */
	private String[] getPosts(String actionType) {
		RestClient client = new RestClient(getString(R.string.AppConnection));
		client.AddParam("action", actionType);

		if (actionType == "getMoreRSS")
			client.AddParam("cursorStr", cursorString);
		client.AddParam("rssUrl", selectedChannel.getRssLink());
		String[] posts = null;
		try {
			client.Execute(RequestMethod.GET);

			String response = "";
			response = client.getResponse();
			if (response == null || response.length() == 0)
				return null;

			cursorString = response.substring(response.lastIndexOf("~") + 1,
					response.length() - 1);
			response = response.substring(0, response.lastIndexOf("~"));

			posts = response.split("~");
		} catch (Exception e) {
		}
		return posts;
	}

	private class PostAdapter extends ArrayAdapter<Post> {

		private ArrayList<Post> items;

		public PostAdapter(Context context, int textViewResourceId,
				ArrayList<Post> items) {
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
			Post o = items.get(position);
			if (o != null) {
				TextView tt = (TextView) v.findViewById(R.id.toptext);
				if (tt != null) {
					tt.setText(o.getPostHeading());
				}
				/* setting the proper image for the channel */
				boolean notSet = true;
				ImageView img = (ImageView) v.findViewById(R.id.icon);
				if(notSet){
				if(selectedChannel.getName().toString().contains("Aaj Tv")) {
					img.setImageDrawable(getResources().getDrawable(R.drawable.aaj_tv));
					notSet = false;
				}
				else if(selectedChannel.getName().toString().contains("The News")) {
					img.setImageDrawable(getResources().getDrawable(R.drawable.the_news));					
					notSet = false;
					}
				else if(selectedChannel.getName().toString().contains("CNN")) {
					img.setImageDrawable(getResources().getDrawable(R.drawable.cnn));
					notSet = false;
				}
				else if(selectedChannel.getName().toString().contains("Nation")) {
					img.setImageDrawable(getResources().getDrawable(R.drawable.the_nation));
					notSet = false;
				}
				else if(selectedChannel.getName().toString().contains("BBC")) {
					img.setImageDrawable(getResources().getDrawable(R.drawable.bbc));
					notSet = false;
				}
				else if(selectedChannel.getName().toString().contains("Fox News")) {
					img.setImageDrawable(getResources().getDrawable(R.drawable.fox_news_logo));
					notSet = false;
				}
				else if(selectedChannel.getName().toString().contains("New York Times")) {
					img.setImageDrawable(getResources().getDrawable(R.drawable.new_york_times));
					notSet = false;
				}
				else if(selectedChannel.getName().toString().contains("BigNewsNetwork")) {
					img.setImageDrawable(getResources().getDrawable(R.drawable.big_news_net));
					notSet = false;
				}
				else if(selectedChannel.getName().toString().contains("CBC -")) {
					img.setImageDrawable(getResources().getDrawable(R.drawable.cbc_news));
					notSet = false;
				}
				else if(selectedChannel.getName().toString().contains("OnePak")) {
					img.setImageDrawable(getResources().getDrawable(R.drawable.one_pakistan));
					notSet = false;
				}
				}
			}
			return v;
		}
	}

	@Override
	protected Dialog onCreateDialog(int id) {
//		myProgressDialog = ProgressDialog.show(News.group, "",
//				"Loading.Please wait...", false);
		myProgressDialog = ProgressDialog.show(context, "",
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
			startFetchingPosts();
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