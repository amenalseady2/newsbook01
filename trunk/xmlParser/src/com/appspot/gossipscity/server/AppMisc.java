/**
 * @author FAIZAN
 *
 */
package com.appspot.gossipscity.server;

import java.util.HashMap;
import java.util.List;
import java.util.Map;

import javax.jdo.PersistenceManager;
import javax.jdo.Query;
import org.datanucleus.store.appengine.query.JDOCursorHelper;

import com.google.appengine.api.datastore.Cursor;

public class AppMisc {

	public static String cursorString;


	public static void createNewPost(String name, String rssLink, int ratingCount) {
		PersistenceManager pm = PMF.get().getPersistenceManager();
		Channels chnl = null;
		ratingCount=0;
		name = removeInvalidCharacters(name);
		rssLink = removeInvalidCharacters(rssLink);

		if (name.length() == 0 || rssLink.length() == 0)
			return;

		try {
			chnl = new Channels();
			chnl.setBasicInfo(name, rssLink, ratingCount);
			//SearchJanitor.updateFTSStuffForGuestBookEntry(chnl);
			pm.makePersistent(chnl);
		} catch (Exception e) {
			e.printStackTrace();
		} finally {
			pm.close();
		}
	}

	/**
	 * It removes tilda and pipe character
	 */
	private static String removeInvalidCharacters(String strPost) {
		if (strPost.contains("|"))
			strPost = strPost.replace("|", " ");

		if (strPost.contains("~"))
			strPost = strPost.replace("~", " ");

		return strPost;
	}

	public static List<Channels> getChannels() {
		List<Channels> rslt = null;
		PersistenceManager pm = PMF.get().getPersistenceManager();
		Query query = pm.newQuery(Channels.class);
		query.setOrdering("ratingCount desc");

		try {
			query.setRange(0, 30);
			rslt = (List<Channels>) query.execute();
			Cursor cursor = JDOCursorHelper.getCursor(rslt);
			cursorString = cursor.toWebSafeString();
			
		} catch (Exception e) {
			e.printStackTrace();
		} finally {
			// pm.close();
		}
		return (List<Channels>) pm.detachCopyAll(rslt);
	}

	public static List<Channels> getMorePosts(String cursorString) {
		Query query = null;
		List<Channels> rslt = null;
		PersistenceManager pm = PMF.get().getPersistenceManager();
		try {
			query = pm.newQuery(Channels.class);
			query.setOrdering("ratingCount desc");
			if (cursorString != null) {
				Cursor cursor = Cursor.fromWebSafeString(cursorString);
				Map<String, Object> extensionMap = new HashMap<String, Object>();
				extensionMap.put(JDOCursorHelper.CURSOR_EXTENSION, cursor);
				query.setExtensions(extensionMap);
			}
			query.setRange(0, 30);
			rslt = (List<Channels>) query.execute();

			Cursor cursor = JDOCursorHelper.getCursor(rslt);
			AppMisc.cursorString = cursor.toWebSafeString();

		} catch (Exception e) {
			e.printStackTrace();
		} finally {
			// query.closeAll();
			// pm.close();
		}
		return rslt;
	}
	
//	public static List<Channels> searchPosts(String searchTxt) {
//		List<Channels> rslt = null;
//		PersistenceManager pm = PMF.get().getPersistenceManager();
//		Query query = pm.newQuery(Channels.class);
//		query.setFilter("postDetail >= :l && postDetail < :2");
//		query.setRange(0, 30);
//
//		rslt = (List<Channels>) query.execute(searchTxt, (searchTxt + "\ufffd"));
//		Cursor cursor = JDOCursorHelper.getCursor(rslt);
//		cursorString = cursor.toWebSafeString();
//		return rslt;
//	}

//	public static int IncrementAgreeCount(String mId) {
//		int newCount = -1;
//		Channels postToPersist;
//		PersistenceManager pm = PMF.get().getPersistenceManager();
//		Query query = pm.newQuery("select from " + Channels.class.getName()
//				+ " where id == " + mId);
//		List<Channels> post = (List<Channels>) query.execute();
//		if (post.size() == 1) {
//			newCount = post.get(0).getAgreeCount() + 1;
//			try {
//				postToPersist = new Channels();
//				postToPersist = post.get(0);
//
//				postToPersist.setAgreeCount(newCount);
//				pm.makePersistent(postToPersist);
//			} catch (Exception e) {
//				e.printStackTrace();
//			} finally {
//				query.closeAll();
//				pm.close();
//			}
//		}
//		return newCount;
//	}

//	public static int IncrementDisagreeCount(String mId) {
//		int newCount = -1;
//		Channels postToPersist;
//		PersistenceManager pm = PMF.get().getPersistenceManager();
//		Query query = pm.newQuery("select from " + Channels.class.getName()
//				+ " where id == " + mId);
//		List<Channels> post = (List<Channels>) query.execute();
//		if (post.size() == 1) {
//			newCount = post.get(0).getDisagreeCount() + 1;
//			postToPersist = new Channels();
//			postToPersist = post.get(0);
//			try {
//				postToPersist.setDisagreeCount(newCount);
//				pm.makePersistent(postToPersist);
//			} catch (Exception e) {
//				e.printStackTrace();
//			} finally {
//				query.closeAll();
//				pm.close();
//			}
//		}
//		return newCount;
//	}

//	public static String GetAgreeDisagreeCount(String id) {
//		String rslt = "";
//		Query query = null;
//		PersistenceManager pm = PMF.get().getPersistenceManager();
//		try {
//			query = pm.newQuery("select from " + Channels.class.getName()
//					+ " where id == " + id);
//			List<Channels> post = (List<Channels>) query.execute();
//			if (post.size() == 1) {
//				rslt = String.valueOf(post.get(0).getAgreeCount());
//				rslt = rslt + "|"
//						+ String.valueOf(post.get(0).getDisagreeCount());
//			}
//		} catch (Exception e) {
//			e.printStackTrace();
//		} finally {
//			query.closeAll();
//			pm.close();
//		}
//		return rslt;
//	}

	

//	public static List<Channels> getMoreSearchRec(String cursorString,
//			String searchTxt) {
//		PersistenceManager pm = PMF.get().getPersistenceManager();
//		List<Channels> searchResults = null;
//		try {
//			searchResults =(List<Channels>) SearchJanitor.searchMoreRecords(searchTxt, pm);
//
//		} catch (Exception e) {
//			e.printStackTrace();
//		} finally {
//			// query.closeAll();
//			// pm.close();
//		}
//		return searchResults;
////		Query query = null;
////		List<Posts> rslt = null;
////		PersistenceManager pm = PMF.get().getPersistenceManager();
////		try {
////			query = pm.newQuery(Posts.class);
////			// query.setOrdering("id desc");
////			//query.setFilter("postDetail >= :l && postDetail < :2");
////			if (cursorString != null) {
////				Cursor cursor = Cursor.fromWebSafeString(cursorString);
////				Map<String, Object> extensionMap = new HashMap<String, Object>();
////				extensionMap.put(JDOCursorHelper.CURSOR_EXTENSION, cursor);
////				query.setExtensions(extensionMap);
////			}
////			query.setRange(0, 30);
//////			rslt = (List<Posts>) query.execute(searchTxt,
//////					(searchTxt + "\ufffd"));
////			rslt = (List<Posts>) query.execute();
////
////			Cursor cursor = JDOCursorHelper.getCursor(rslt);
////			AppMisc.cursorString = cursor.toWebSafeString();
////
////		} catch (Exception e) {
////			e.printStackTrace();
////		} finally {
////			// query.closeAll();
////			// pm.close();
////		}
////		return rslt;
//	}

//	public static List<Channels> mySearch(String searchString) {
//		PersistenceManager pm = PMF.get().getPersistenceManager();
//		List<Channels> searchResults = null;
//		try {
//			searchResults =(List<Channels>) SearchJanitor.searchGuestBookEntries(
//					searchString, pm);
//
//		} catch (Exception e) {
//			e.printStackTrace();
//		} finally {
//			// query.closeAll();
//			// pm.close();
//		}
//		return searchResults;
//	}
}
