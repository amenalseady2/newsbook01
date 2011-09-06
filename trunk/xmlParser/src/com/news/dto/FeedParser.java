package com.news.dto;

import java.util.ArrayList;
import java.util.List;

import javax.xml.parsers.DocumentBuilder;
import javax.xml.parsers.DocumentBuilderFactory;
import javax.xml.transform.OutputKeys;
import javax.xml.transform.Transformer;
import javax.xml.transform.TransformerFactory;
import org.w3c.dom.Document;
import org.w3c.dom.Element;
import org.w3c.dom.NodeList;

public class FeedParser {

	public static List<String> mainParser(String feedURL) {
		// "http://feeds.bbci.co.uk/news/rss.xml";
		List<String> ls = new ArrayList<String>();
		try {
			DocumentBuilderFactory factory = DocumentBuilderFactory
					.newInstance();
			DocumentBuilder builder = factory.newDocumentBuilder();
			Document doc = builder.parse(feedURL);
			NodeList Std = doc.getElementsByTagName("item");
			Transformer tFormer = TransformerFactory.newInstance()
					.newTransformer();
			tFormer.setOutputProperty(OutputKeys.METHOD, "text");

			for (int i = 0; i < Std.getLength(); i++) {
				Element StudentData = (Element) (Std.item(i));
				NodeList headings = StudentData.getElementsByTagName("title");
				NodeList discription = StudentData
						.getElementsByTagName("description");
				String id = headings.item(0).getFirstChild().getNodeValue();
				String dob = discription.item(0).getFirstChild().getNodeValue();
				ls.add("Title: " + id + "\n" + "Detail :" + dob);
				ls.add("" + id + "~\n");
			}
		} catch (Exception e) {
			System.exit(0);
		}
		return ls;
	}
}
