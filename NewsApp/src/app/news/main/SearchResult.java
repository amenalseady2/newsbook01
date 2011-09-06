package app.news.main;

import java.util.ArrayList;

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
import android.widget.ListView;
import android.widget.TextView;
import android.widget.Toast;

import com.news.client.RequestMethod;
import com.news.client.RestClient;
import com.news.entities.Post;



public class SearchResult extends ListActivity {
	private ArrayList<Post> m_posts = null;
	public PostAdapter m_searchAdapter;
	private static String cursorString;
	private String txtSearch;
	private static String actionType;
	public ProgressDialog mProgressDialog = null;
	private String[] mainPosts = null;
	private final Context context = this;

	/*
	 * some private variables to implement endless scrolling
	 */
	private int visibleThreshold = 3;
	private int previousTotal = 0;
	private boolean loading = true;

	@Override
	public void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		setContentView(R.layout.tt_main);

		Intent intent = getIntent();
		txtSearch = intent.getExtras().getString("txtSearch");

		m_posts = new ArrayList<Post>();
		this.m_searchAdapter = new PostAdapter(this, R.layout.row, m_posts);
		setListAdapter(this.m_searchAdapter);

		// ///////////////////////////////////////////////////////////////
		// Post temp = new Post();
		// for (int index = 0; index < 10; index++) {
		// temp = new Post();
		// temp.setPostDetail(String.valueOf(index));
		// this.m_searchAdapter.add(temp);
		// }
		// this.m_searchAdapter.notifyDataSetChanged();
		// ///////////////////////////////////////////////////////////////

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

				launchViewPostInDetail.putExtra("postDetail", selectedItem
						.getPostDetail().toString());
//				launchViewPostInDetail.putExtra("agreeCount",
//						selectedItem.getAgreeCount());
//				launchViewPostInDetail.putExtra("disagreeCount",
//						selectedItem.getDisagreeCount());
//				launchViewPostInDetail.putExtra("id", selectedItem.getId());

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
				}

				if (!loading
						&& ((totalItemCount - visibleItemCount) <= (firstVisibleItem + visibleThreshold))) {
					SearchResult.actionType = "getMoreSearchRec";
					new LoadMoreEntries().execute(null, null, null);
					loading = true;
				}
			}
		});

		SearchResult.actionType = "searchPosts";
		new LoadMoreEntries().execute(null, null, null); // loads the entries
															// for the first
															// time

	}

	/*
	 * It notifies the UI Thread to make changes to the Adapter List.
	 */
	private void notifyChange() {
		if (m_posts != null && m_posts.size() > 0) {
			m_searchAdapter.notifyDataSetChanged();
			for (int i = 0; i < m_posts.size(); i++)
				m_searchAdapter.add(m_posts.get(i));
		} else {
			Toast.makeText(SearchResult.this, "No More Posts exists",
					Toast.LENGTH_LONG).show();
		}
		m_searchAdapter.notifyDataSetChanged();
		m_posts = null;
	}

	/*
	 * It Performs a full routine of records fetching in a particular format.
	 * Then parse that format to extract information and full the m_posts
	 * ArrayList
	 */
	private void startFetchingPosts() {

		try {
			mainPosts = getPosts(SearchResult.actionType);
			if (mainPosts == null || mainPosts.length == 0)
				return;
			String[] unitPost;
			m_posts = new ArrayList<Post>();
			Post o1 = new Post();

			/*
			 * Record Format id||detail||agreeCount||disagreeCount
			 */

			for (int index = 0; index < mainPosts.length; index++) {

				unitPost = mainPosts[index].split("\\|");

				if (unitPost.length == 4) {

					o1 = new Post();

//					o1.setId(unitPost[0].toString());
					o1.setPostDetail(unitPost[1].toString());
//					o1.setAgreeCount(Integer.parseInt(unitPost[2].toString()));
//					o1.setDisagreeCount(Integer.parseInt(unitPost[3].toString()));

					m_posts.add(o1);
				}
			}

		} catch (Exception e) {
			e.printStackTrace();
		}
	}

	/*
	 * It gets the post from the App Engine and split the result using tilda(~)
	 */
	private String[] getPosts(String actionType) {
		RestClient client = new RestClient(getString(R.string.AppConnection));
		client.AddParam("action", actionType);
		
		if (actionType == "getMoreSearchRec")
			client.AddParam("cursorStr", cursorString);
		
		client.AddParam("txtSearch", txtSearch);

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
					tt.setText(o.getPostDetail());
				}
			}
			return v;
		}
	}

	private class LoadMoreEntries extends AsyncTask<Void, Void, Void> {

		@Override
		protected void onPreExecute() {

			super.onPreExecute();
			mProgressDialog = ProgressDialog.show(getParent(), "",
					"Loading.Please wait...", false);

		}

		@Override
		protected Void doInBackground(Void... params) {

			startFetchingPosts();
			return null;
		}

		@Override
		protected void onPostExecute(Void result) {
			super.onPostExecute(result);
			mProgressDialog.dismiss();
			notifyChange();

		}
	}
}
