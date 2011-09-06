package com.appspot.gossipscity.server;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;
import java.util.Set;
import java.util.logging.Logger;

import javax.jdo.PersistenceManager;
import javax.jdo.Query;

import org.datanucleus.store.appengine.query.JDOCursorHelper;

import com.appspot.gossipscity.Posts;
import com.google.appengine.api.datastore.Cursor;
import com.google.appengine.api.datastore.DatastoreNeedIndexException;
import com.google.appengine.api.datastore.DatastoreTimeoutException;

public class SearchJanitor {

	private static final Logger log = Logger.getLogger(SearchJanitor.class
			.getName());

	public static final int MAXIMUM_NUMBER_OF_WORDS_TO_SEARCH = 5;

	public static final int MAX_NUMBER_OF_WORDS_TO_PUT_IN_INDEX = 200;

	public static List<Channels> searchGuestBookEntries(String queryString,
			PersistenceManager pm) {

		StringBuffer queryBuffer = new StringBuffer();

		queryBuffer.append("SELECT FROM " + Channels.class.getName() + " WHERE ");

		Set<String> queryTokens = SearchJanitorUtils
				.getTokensForIndexingOrQuery(queryString,
						MAXIMUM_NUMBER_OF_WORDS_TO_SEARCH);

		List<String> parametersForSearch = new ArrayList<String>(queryTokens);

		StringBuffer declareParametersBuffer = new StringBuffer();

		int parameterCounter = 0;

		while (parameterCounter < queryTokens.size()) {

			queryBuffer.append("fts == param" + parameterCounter);
			declareParametersBuffer.append("String param" + parameterCounter);

			if (parameterCounter + 1 < queryTokens.size()) {
				queryBuffer.append(" && ");
				declareParametersBuffer.append(", ");
			}
			parameterCounter++;

		}

		Query query = pm.newQuery(queryBuffer.toString());

		query.declareParameters(declareParametersBuffer.toString());

		List<Channels> result = null;

		query.setRange(0, 30);

		try {
			result = (List<Channels>) query.executeWithArray(parametersForSearch
					.toArray());
			Cursor cursor = JDOCursorHelper.getCursor(result);
			AppMisc.cursorString = cursor.toWebSafeString();
		} catch (DatastoreTimeoutException e) {
			log.severe(e.getMessage());
			log.severe("datastore timeout at: " + queryString);// +
																// " - timestamp: "
																// +
																// discreteTimestamp);
		} catch (DatastoreNeedIndexException e) {
			log.severe(e.getMessage());
			log.severe("datastore need index exception at: " + queryString);// +
																			// " - timestamp: "
																			// +
																			// discreteTimestamp);
		}

		return result;

	}

	public static List<Channels> searchMoreRecords(String queryString,
			PersistenceManager pm) {

		StringBuffer queryBuffer = new StringBuffer();

		queryBuffer.append("SELECT FROM " + Channels.class.getName() + " WHERE ");

		Set<String> queryTokens = SearchJanitorUtils
				.getTokensForIndexingOrQuery(queryString,
						MAXIMUM_NUMBER_OF_WORDS_TO_SEARCH);

		List<String> parametersForSearch = new ArrayList<String>(queryTokens);

		StringBuffer declareParametersBuffer = new StringBuffer();

		int parameterCounter = 0;

		while (parameterCounter < queryTokens.size()) {

			queryBuffer.append("fts == param" + parameterCounter);
			declareParametersBuffer.append("String param" + parameterCounter);

			if (parameterCounter + 1 < queryTokens.size()) {
				queryBuffer.append(" && ");
				declareParametersBuffer.append(", ");

			}

			parameterCounter++;

		}

		Query query = pm.newQuery(queryBuffer.toString());

		query.declareParameters(declareParametersBuffer.toString());

		List<Channels> result = null;
		if (AppMisc.cursorString != null) {
			Cursor cursor = Cursor.fromWebSafeString(AppMisc.cursorString);
			Map<String, Object> extensionMap = new HashMap<String, Object>();
			extensionMap.put(JDOCursorHelper.CURSOR_EXTENSION, cursor);
			query.setExtensions(extensionMap);
		}

		query.setRange(0, 30);

		try {
			result = (List<Channels>) query.executeWithArray(parametersForSearch
					.toArray());
			Cursor cursor = JDOCursorHelper.getCursor(result);
			AppMisc.cursorString = cursor.toWebSafeString();
		} catch (DatastoreTimeoutException e) {
			log.severe(e.getMessage());
			log.severe("datastore timeout at: " + queryString);// +
																// " - timestamp: "
																// +
																// discreteTimestamp);
		} catch (DatastoreNeedIndexException e) {
			log.severe(e.getMessage());
			log.severe("datastore need index exception at: " + queryString);// +
																			// " - timestamp: "
																			// +
																			// discreteTimestamp);
		}

		return result;

	}

	public static void updateFTSStuffForGuestBookEntry(Channels post) {

		StringBuffer sb = new StringBuffer();

		sb.append(post.getPostDetail());

		Set<String> new_ftsTokens = SearchJanitorUtils
				.getTokensForIndexingOrQuery(sb.toString(),
						MAX_NUMBER_OF_WORDS_TO_PUT_IN_INDEX);

		Set<String> ftsTokens = post.getFts();

		// ftsTokens.clear();

		for (String token : new_ftsTokens) {
			ftsTokens.add(token);

		}
	}

}
