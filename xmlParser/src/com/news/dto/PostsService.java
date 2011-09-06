/**
 * @author FAIZAN
 *
 */
package com.news.dto;

import java.util.ArrayList;

public interface PostsService {
	 
	  Boolean deletePost(Long id);

	  ChannelDTO getPost(Long id);

	  ChannelDTO updatePost(ChannelDTO post);
}
