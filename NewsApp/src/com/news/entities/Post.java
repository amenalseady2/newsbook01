package com.news.entities;

public class Post {
	
    private String postDetail;
    private String postHeading;
    
    public String getPostHeading() {
		return postHeading;
	}
	public void setPostHeading(String postHeading) {
		this.postHeading = postHeading;
	}
	public String getPostDetail() {
		return postDetail;
	}
	public void setPostDetail(String postDetail) {
		this.postDetail = postDetail;
	}
}