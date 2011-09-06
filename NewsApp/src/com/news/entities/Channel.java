package com.news.entities;

public class Channel {
	
	private int id;
	private String name;
	private String RssLink;
	private int flag;
	
   
	public int getFlag() {
		return flag;
	}

	public void setFlag(int ratingCount) {
		this.flag = ratingCount;
	}

	public String getRssLink() {
		return RssLink;
	}

	public void setName(String name) {
		this.name = name;
	}

	public void setRssLink(String rssLink) {
		RssLink = rssLink;
	}

	public int getId() {
		return id;
	}

	public void setId(int id) {
		this.id = id;
	}

	public String getName() {
		return name;
	}
}