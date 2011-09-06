/** 
 * Copyright 2010 Daniel Guermeur and Amy Unruh4	
 *
 *   Licensed under the Apache License, Version 2.0 (the "License");
 *   you may not use this file except in compliance with the License.
 *   You may obtain a copy of the License at
 *
 *       http://www.apache.org/licenses/LICENSE-2.0
 *
 *   Unless required by applicable law or agreed to in writing, software
 *   distributed under the License is distributed on an "AS IS" BASIS,
 *   WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *   See the License for the specific language governing permissions and
 *   limitations under the License.
 *
 *   See http://connectrapp.appspot.com/ for a demo, and links to more information 
 *   about this app and the book that it accompanies.
 */
package com.news.dto;

import java.io.Serializable;

/**
 * The 'detailed' Data Transfer Object for the Channel class
 */
@SuppressWarnings("serial")
public class ChannelDTO implements Serializable {

	private Long id;
	private String name;
	private String rssLink;
	private int ratingCount;
	
	public ChannelDTO() {
	}

	public ChannelDTO(String postDetail, int agreeCount, int disagreeCount) {
		this();
		setBasicInfo(name, rssLink, ratingCount);
	}

	public void setBasicInfo(String name, String rssLink,
			int ratingCount) {
		this.name = name;
		this.rssLink = rssLink;
		this.ratingCount = ratingCount;
	}

	public Long getId() {
		return id;
	}

	public void setId(Long id) {
		this.id = id;
	}

	public String getName() {
		return name;
	}

	public void setName(String name) {
		this.name = name;
	}

	public String getRssLink() {
		return rssLink;
	}

	public void setRssLink(String rssLink) {
		this.rssLink = rssLink;
	}

	public int getRatingCount() {
		return ratingCount;
	}

	public void setRatingCount(int ratingCount) {
		this.ratingCount = ratingCount;
	}

}
