<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>You Cure Me</title>
<?php include "header_new.php"; ?>
<?php 
if(isset($_POST["login_button"])=="signin")
{
	$email = $_POST["email"];
	$password = $_POST["pwd"];
	
	//First check whether this user was a disabled one.
	$query="select userid,fname,lname,email,usertypeid,user_previlege,isactive,thumb_profile as profilepic from tbluser where email='".$email."' AND password='".$password."'
			AND isactive=0 AND user_previlege=0";
	$result=mysql_query($query);
	$num=mysql_num_rows($result);
	if($num > 0)
	{ 
		  // Disabled user. Redirect the user for reporting this to the administrator
		  echo "<script>location.href='bugreport.php?msg=Your Email has been disabled. Please contact the System Administrator' </script>";
 	}
	
	//user is not a disabled one.
	$query="select userid,fname,lname,email,usertypeid,user_previlege,isactive,thumb_profile as profilepic from tbluser where email='".$email."' AND password='".$password."' AND isactive=1";
	$result=mysql_query($query);
	$num=mysql_num_rows($result);
	if($num > 0)
	{
		$row=mysql_fetch_array($result);   
		
		if($row["user_previlege"] == 0)
		{
		  // Disabled user. Redirect the user for reporting this to the administrator
		  echo "<script>location.href='bugreport.php?msg=Your Email has been disabled. Please contact the System Administrator' </script>";
		}
		
		$_SESSION["userid"]=$row["userid"];
		$_SESSION["fname"] = $row["fname"]." ".$row["lname"];
		$_SESSION["email"] = $row["email"];
		$_SESSION["usertypeid"]=$row["usertypeid"];		
		
		if($row["profilepic"]!="")
			$_SESSION["profilepic"]=$row["profilepic"];
		else
			$_SESSION["profilepic"]="empty_profile.jpg";
		
		echo "<script>location.href='myprofile.php' </script>";		
	}
	else
	{		
		echo "<script>location.href='index.php?msg=Invalid login username/password.' </script>";
	}
}


?>

</head>
<body>

  <div id="wrapper">  
  	<div id="top_header">
    	<div id="logo">
        <?php if (isset($_SESSION['email'])) {?>
        	<a href="myprofile.php"><img src="images/logo_2.png" alt="You Cure Me"></a>
            <?php } else if (isset($_SESSION['admin'])== 5) {
				 ?><a href="viewuser.php"><img src="images/logo_2.png" alt="You Cure Me"></a> <?php } else { ?>
              <a href="index.php"><img src="images/logo_2.png" alt="You Cure Me"></a> <?php } ?>
        </div>
        
        <div id="layer1">
                  <span id="close">
                  <a href="javascript:setVisible('layer1')" style="text-decoration: none"><img src="images/close.png" alt="Close"></a>
                  </span>
                  <h2>You Cure Me Login</h2>
                  <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post"  onSubmit="return signins(this);">
                  <div>
                  <label>
                  Your Email: <span class="required">*</span> 
                  </label>
                  </div>
                  <div>
                 <input class="text_feild" name="email" id="email" type="text" />
                  </div>
                  <div>
                  <label>
                  Password <span class="required">*</span> 
                  </label>
                  </div>
                  <div>
                 <input class="text_feild" name="pwd" id="pwd" type="password" />
                    </div>
                    <div>
                     
                  <button type="submit"  name="login_button" id="login_button" value="signin" class="submit">
                  </button>
                  <span style="padding-top:16px;"><a href="recover.php">Forgot your password?</a> </span>
                  </div>
                
                  </form>
    </div>
        <div class="right_links">
        	 <?php require("right_login.php");?>
        </div>
        </div>       
  <div class="clear"></div>
           
         <div class="top">
                <div class="bottom_footer">
           <h2 class="h2heads">UCUREME TERMS AND CONDITIONS</h2>   
          <p class="headcontent">
          These Terms of Use constitute an agreement between you and UCureMe, Inc. ("UCureMe"). The terms of this Agreement govern your use of UCureMe's website at <a href="http://www.ucureme.com" target="_blank" class="links">www.ucureme.com</a>.
          </p>
        <strong>Visitors or registered members agree to be bound by the terms of this Agreement </strong>  
<h2 class="smallheadings">DESCRIPTION OF SERVICE:</h2>
 <p class="headcontent">This Agreement contains the complete terms and conditions that apply to your participation in UCureMe.com, "the site". The Agreement describes and encompasses the entire agreement between us and you, and supersedes all prior or contemporaneous agreements, representations, warranties and understandings with respect to the Site, the content and computer programs provided by or through the Site, and the subject matter of this Agreement. If you wish to become a member of this social networking website and communicate with other members, please read these terms of use carefully before using our site and its services. By accessing this site or using any part of the site or any content or services hereof, you agree to become bound by these terms and conditions. If you do not agree to all the terms and conditions, then you may not access the site or use the content or any services in the site. Amendments to this agreement can be made and effected by us from time to time without specific notice to your end. Agreement posted on the Site reflects the latest agreement and you should carefully review the same before you use our site. The Site provides a service whereby the general public, patients and researchers come together to share information. The Service includes access to the UCureMe public website, including user profiles, and other features that enhance the Service. The Service is provided "AS-IS" and UCureMe assumes no responsibility for the use of the Service outside the terms of this Agreement or other applicable terms. The form and nature of the Service, which UCureMe provides, may change from time to time without prior notice to you. You acknowledge that UCureMe does not control in any manner the nature, quality, or accuracy of user-rated content, such as resource posts, comments, and communications with other members. You agree that UCureMe is a neutral forum for people coming together in the search for ways to share stories, resources and improve the well being for all. </p>
<h2 class="smallheadings">ELIGIBILITY</h2> 
 <p class="headcontent">This website including its tools, applications and services are intended solely for access and use by individual who are at least eighteen (18) years old and above. By accessing and using our website including its tools, applications and services, you warrant and represent that you are at least eighteen (18) years old and with full authority, right, and capacity to enter into this Agreement and to abide by all of the terms and conditions of this Agreement. </p>
 <h2 class="smallheadings">USE OF THE SITE & PROHIBITIONS</h2> 
 <p class="headcontent">This site allows you to join social networks, gain friends, mingle with your love ones, express your thoughts, and make some comments and discussion, and communicate with other members. You understand and agree, however, that you will use this site including its tools and services with full sense of responsibility and in a manner that is consistent with these Terms and in such a way as to ensure compliance with all applicable laws and regulations. You agree that you will use the site and its services in compliance with all applicable local, state, national, and international laws, rules and regulations, including any laws regarding the transmission of technical data exported from your country of residence and all United States export control laws.</p>
 <p class="headcontent">You may view, download for collection purposes only, and print pages or other contents from the website for your own personal use, subject to the restrictions set out below and elsewhere in these terms of use and will not take any action on UCureMe. </p><br />
<p class="headcontent">(a) You must not use our sites, including its services and or tools if you are not able to form legally binding contracts, are under the age of 18, or are temporarily or indefinitely suspended from using our sites, services, or tools </p>
<p class="headcontent">(b) You should not post any blogs, items, messages, and or contents that are inappropriate and fails to observe sense of decency and or would offend other persons;</p>
<p class="headcontent">(c) You must not republish, sell, rent or sub-license any materials from this website including republication on another website; </p>
<p class="headcontent">(d) You must not collect and disclose information about users' personal information about UCureMe members to send SPAM of any kind</p>
<p class="headcontent">(e) You must not reproduce, duplicate, copy or otherwise exploit material on our website for a commercial purpose</p>
<p class="headcontent">(f)You agree not to post or upload anything to the Site that is inaccurate, false or misleading; is obscene, abusive or indecent; or that infringes any copyright, patent, trademark, trade secret or other proprietary rights or rights of any party. </p>
<p class="headcontent">(g) You must not post false, inaccurate, misleading, defamatory, or libelous content or to transmit content that is unlawful, harassing, libelous, invasive of another's privacy, harmful, vulgar, obscene, or otherwise objectionable, may result in removal of content and/or termination of your service. </p>
<p class="headcontent">(h) You must not take any action that may damage the rating system.</p>
<p class="headcontent">(i) You must not use our website in any way that causes, or may cause, damage to the website or impairment of the availability or accessibility of the website; or in any way which is unlawful, illegal, fraudulent or harmful, or in connection with any unlawful, illegal, fraudulent or harmful purpose or activity or by posting content on the Site that contains viruses, Trojan horses, worms, time bombs, spiders, or other programs intended to damage, interfere with, intercept or expropriate any system, data or personal information;</p>
<p class="headcontent">(j)You understand that using the Site for an illegal purpose </p>
<p class="headcontent">(k) You must not use any robot, spider, scraper, or other automated means to access the Site or content provided on the Site for any purposes; </p>
<p class="headcontent">(l)You will not publicize or offer any contest. </p>
<p class="headcontent">(m) You will not post anyone's identification documents or sensitive financial information on the site.</p>
<p class="headcontent">(n) If we disable your account, you will not create another one without our permission</p>
<p class="headcontent">(o) You will not facilitate any violations of this Statement. </p>
<h2 class="smallheadings">UCUREME DOES NOT PROVIDE MEDICAL ADVICE</h2>
<p class="headcontent">Any and all information on the Site is for informational purposes only. It is not a substitute for professional medical advice or treatment and is not intended to be used to diagnose, cure, treat, or prevent any condition or disease, or to assess the status of your health. As part of your participation in the site you contribute your information, including the ongoing surveys to aggregate, open research efforts to study diseases and work towards finding the causes of illness. </p>
<p class="headcontent">Use of on any information provided by UCureMe, on persons appearing on the Site at the invitation of UCureMe, or on other members or visitors is solely at your own risk. </p>
<h2 class="smallheadings">REGISTRATION / MEMBER ACCOUNT</h2>
<p class="headcontent">As a condition of becoming a member of this social networking website including the use of its tools, applications and services, you are required to register with the site and be required to provide password and user name. You must complete the full registration process and shall provide the site with accurate, complete, and updated registration information. Failure to do so shall constitute a breach of the Terms of Use, which may result in immediate termination of your account. </p>
<p class="headcontent">You are entirely responsible for maintaining the confidentiality of your password. You agree not to use the Member Account, Member profile, username, or password of another Member at any time. You agree to notify us immediately if you suspect any unauthorized use of your Member Account or Member profile or access to your password. You are solely responsible for any and all use of your Member Account and Member profile. You must not transmit any worms or viruses or any code of a destructive nature. Any information provided by you or gathered by the site or third parties during any visit to the site shall be subject to the terms of youcureme.com's Privacy Policy.</p>
<p class="headcontent">In addition, you may not register for more than one Member Account, register for a Member Account on behalf of an individual other than yourself or register a Member Account on behalf of any group or entity. Furthermore, you may not use or attempt to use another's Member Account without authorization from us or create a false identity on our Services.</p>
<h2 class="smallheadings">NON-COMMERCIAL USE BY MEMBERS. </h2>
<p class="headcontent">Members on this social networking website are prohibited to use the services of the website in connection with any commercial endeavors or ventures. This includes providing links to other websites, whether deemed competitive to this website or not. Juridical persons or entities including but not limited to organizations, companies, and/or businesses may not become Members of <a href="http://www.youcureme.com" target="_blank" class="links">youcureme.com</a> and should not use the site for any purpose. </p>
<h2 class="smallheadings">LINKS & FRAMINGS</h2>
<p class="headcontent">Illegal and/or unauthorized uses of the Services, including unauthorized framing of or linking to the Sites will be investigated, and appropriate legal action may be taken. Some links, however, are welcome to the site and you are allowed to establish hyperlink to appropriate part within the site provided that: (i) you post your link only within the forum, chat or message board section; (ii) you do not remove or obscure any advertisements, copyright notices or other notices on the placed at the site; (iii) the link does not state or imply any sponsorship or endorsement of your site and (iv) you immediately stop providing any links to the site on written notice from us. However, you must check the copyright notice on the homepage to which you wish to link to make sure that one of our content providers does not have its own policies regarding direct links to their content on our sites. </p>
<h2 class="smallheadings">Posting and Use of Content</h2>
<p class="headcontent">UCureMe will not, at all times, control all of the content posted on the site and does not guarantee the accuracy or quality of such content, nor does UCureMe take any responsibility for the content on the site. In using the site, you may be exposed to content that is offensive, indecent, or objectionable; and you must evaluate, and bear all risks associated with, the use of any content, including any reliance on the accuracy, completeness, or usefulness of the content. You acknowledge that UCureMe has the right but no obligation to prescreen postings and to remove any content that violates this Agreement or is deemed by UCureMe, its sole discretion, to be otherwise objectionable. As a community, it is your responsibility to notify UCureMe of inappropriate or illegal content. </p>
<p class="headcontent">You may post messages, comments, resources, images, information and other content ("Submissions"). You (or the author) own the copyright in the content, but by posting such content you grant UCureMe and our affiliates a nonexclusive, worldwide, royalty free, perpetual, non-revocable license to use, copy, display, perform, distribute, translate, edit, reproduce, transmit, and create derivative works of your Submissions that is subject to the terms of the Privacy Policy. This license includes a right for UCureMe to make such content available to other organizations or individuals with whom UCureMe has relationships. UCureMe, in performing the required technical steps to provide the Service to our users, may transmit or distribute your content through various public networks and in various media and make changes to content as necessary to conform and adapt that content to the technical requirements of connecting networks, devices, services, or media. This license shall permit UCureMe to take these actions. </p>
<p class="headcontent">If you are accessing the site oustside of Canada; you acknowledge and consent to; the transfer of such Information outside your resident jurisdiction; that
UCureMe may collect and use your Information and disclose it to other entities outside your resident jurisdiction. </p>
<p class="headcontent">You agree not to disclose any personally identifiable information about other members that you learn using this Site without the express consent of such member. You may disclose general, non-identifying information to third parties subject to the above restriction on non-commercial use. </p>
<h2 class="smallheadings">CHAT ROOMS, FORUMS, COMMUNICATIONS AND OTHER MATERIALS POSTED BY YOU</h2>
<p class="headcontent">You are responsible for the content of your communications, messages and posts, and its consequences. We reserves the right to terminate your registration if we become aware, at our sole discretion, that you are violating any of the guidelines set forth in this agreement and privacy policy. While we want to encourage an open exchange of information and ideas, yet, we do not review postings made in any chat rooms, forums and other public-posting areas on the site. You can expect these areas to include information and opinions from a variety of individuals and organizations other than us. We do not endorse or guarantee the accuracy, integrity or quality of any posting, regardless of whether the posting comes from a user, from a celebrity or "expert" guest, or from a member of our staff. </p>
<p class="headcontent">By participating in this social networking site, you understand and agree not to post or transmit any material that, in our judgment, is defamatory, abusive, obscene, threatening or unlawful in any way, or any material that infringes on the rights of others or contains any virus or other computer programming routine which may interfere with or damage the site or otherwise interrupt on the ability of others to use or enjoy the same. We reserve the right to delete, move or edit any postings that come to our attention that we consider unacceptable or inappropriate, whether for legal or for any other reason. Furthermore, we reserve the right to deny access to anyone who we believe, in our sole discretion, has in any way breached these Terms or where we reasonably believe a user does not comply with any relevant age restrictions on the site.</p>

<h2 class="smallheadings">WARRANTY DISCLAIMER AND EXCLUSIONS / LIMITATIONS OF LIABILITY</h2>
<p class="headcontent">You represent and warrant that (a) all of the information provided by you to our website to participate in the Service is correct and current; and (b) you have all necessary right, power and authority to enter into this Agreement and to perform the acts required of you hereunder.</p><br />
<p class="headcontent">You hereby accepts and agree that it is beyond our control, and no duty to take any action regarding: which users gain access to the Site or use the Services; what effects the Content may have on you; how you may interpret or use the Content; or what actions you may take as a result of having been exposed to the Content. You release us from all liability for you having acquired or not acquired Content through the Site or the Services.</p><br />
<p class="headcontent">It should note that the Site or Services may contain, or direct you to sites containing, information that some people may find offensive or inappropriate. We make no representations concerning any content contained in or accessed through the Site or Services, and we will not be responsible or liable for the accuracy, copyright compliance, legality or decency of material contained in or accessed through the Site or the Services. Advice, guidance, thoughts, recommendations, conclusions, and all information posted or emailed by site members are not in any way approved or endorsed by UCureMe and you are using this information at your own risk. THE SERVICE, CONTENT, AND SITE ARE PROVIDED ON AN "AS IS" BASIS, WITHOUT WARRANTIES OF ANY KIND, EITHER EXPRESS OR IMPLIED, INCLUDING, WITHOUT LIMITATION, IMPLIED WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE OR NON-INFRINGEMENT. </p><br />
<p class="headcontent">In addition, we make no representation that the operation of our site will be uninterrupted or error-free, and we will not be liable for the consequences of any interruptions or errors. We may change, restrict access to, suspend or discontinued the site or any part of it at anytime. The information, content and services on the site are provided on an "as is" basis. When you use the site and or participate herein, you understand and agree that you participate at your own risk. You understand and agree that UCureMe, employees, officers, partners, contributors, agents, and all affiliates shall not be liable for any direct, indirect, incidental, special, consequential, or exemplary damages. This includes but is not limited to the damages for loss of profits, goodwill, use, data or other intangible losses (even if UCureMe has been advised of such damages or any possibility of), resulting from: (a) the use or the inability to use the site; (b) any action you take based on information you receive through or from the service, (c) your failure to keep confidential your password and/or account details (d) the unauthorized access to or alteration of any of your transmissions or data; or any statements or conduct of any third party on the site. </p><br />
<p class="headcontent">You agree to indemnify and hold harmless UCureMe, its officers, employees, agents, subsidiaries, partners, and affiliates, from and also against any and all claims, actions or demands, liabilities and settlements including without limitation, reasonable legal and accounting fees, resulting from, or alleged to result from you violating this agreement. </p>
<h2 class="smallheadings">INTELLECTUAL PROPERTY RIGHT</h2>
<p class="headcontent">The Web allows people throughout the world to share valuable information, ideas and creative works. To ensure continued open access to such materials, we all need to protect the rights of those who share their creations with us. Although we make the Site freely accessible, we don't intend to give up our rights, or anyone else's rights, to the materials appearing on them. The materials available on the site shall remain the property of youcureme.com and/or its licensors, and are protected by copyright, trademark and other intellectual property laws. You acquire no proprietary interest in any such rights. Furthermore, you may not remove or obscure the copyright notice or any other notices contained in the site or anything retrieved or downloaded from them. </p><br />
<p class="headcontent">You hereby acknowledge that all rights, titles and interests, including but not limited to rights covered by the Intellectual Property Rights, in and to the site, and that You will not acquire any right, title, or interest in or to the site except as expressly set forth in this Agreement. You will not modify, adapt, translate, prepare derivative works from, decompile, reverse engineer, disassemble or otherwise attempt to derive source code from any of our services, software, or documentation, or create or attempt to create a substitute or similar service or product through use of or access to the Program or proprietary information related thereto. </p>
<h2 class="smallheadings">Hyperlinks to Other Websites and Resources</h2> 
<p class="headcontent">UCureMe does not endorse and is not responsible or liable for any content, advertising, products, or other materials on or available from such external sites or resources. </p>
<p class="headcontent">UCureMe is not responsible or liable for any loss or damage of any sort as the result of any such dealings with information providers or resources found on or through the site. </p>
<h2 class="smallheadings">International Laws</h2>
<p class="headcontent">As a user of the site you agree to comply with all of the local rules of online conduct and all applicable laws regarding the transmission of data exported from Canada or the country you access the Site. </p>
<h2 class="smallheadings">Confidentiality</h2>
<p class="headcontent">You agree not to disclose information you obtain from us and or from our clients, advertisers, suppliers and forum members. All information submitted to by an end-user customer pursuant to a Program is proprietary information of youcureme.com. Such customer information is confidential and may not be disclosed. Publisher agrees not to reproduce, disseminate, sell, distribute or commercially exploit any such proprietary information in any manner.</p>
<h2 class="smallheadings">NON-ASSIGNMENT OF RIGHTS</h2>
<p class="headcontent">Your rights of whatever nature cannot be assigned nor transferred to anybody, and any such attempt may result in termination of this Agreement, without liability to us. However, we may assign this Agreement to any person at any time without notice.</p>
<h2 class="smallheadings">Waiver and Severability of Terms.</h2>
<p class="headcontent">Failure of the <a href="http://www.youcureme.com" target="_blank" class="links">youcureme.com</a> to insist upon strict performance of any of the terms, conditions and covenants hereof shall not be deemed a relinquishment or waiver of any rights or remedy that the we may have, nor shall it be construed as a waiver of any subsequent breach of the terms, conditions or covenants hereof, which terms, conditions and covenants shall continue to be in full force and effect. </p>
<p class="headcontent">No waiver by either party of any breach of any provision hereof shall be deemed a waiver of any subsequent or prior breach of the same or any other provision.</p>
<p class="headcontent">In the event that any provision of these Terms and Conditions is found invalid or unenforceable pursuant to any judicial decree or decision, such provision shall be deemed to apply only to the maximum extent permitted by law, and the remainder of these Terms and Conditions shall remain valid and enforceable according to its terms.</p>
<h2 class="smallheadings">Changes and Limits to Service</h2>
<p class="headcontent">UCureMe reserves the right to modify or discontinue, temporarily or permanently, the Service (or any part thereof) with or without notice. You acknowledge that UCureMe may establish general practices and limits concerning use of the Service, including the amount of time content will be retained by the Service, the maximum disk space on UCureMe's servers, and a maximum frequency or duration for accessing the Service. UCureMe has no responsibility or liability for any deletion or failure to store any messages or other content maintained or transmitted by the Service. UCureMe reserves the right to change these limits at any time. </p>
<h2 class="smallheadings">Entire Agreement</h2>
<p class="headcontent">This Agreement shall be governed by and construed in accordance with the substantive laws of Canada, without any reference to conflict-of-laws principles. The Agreement describes and encompasses the entire agreement between you, and us and supersedes all prior or contemporaneous agreements, representations, warranties and understandings with respect to the Site, the contents and materials provided by or through the Site, and the subject matter of this Agreement. UCureMe reserves the right to modify this Agreement at any time, and without prior notice, by posting amended terms on this Site. You understand and agree that if you use the Site after the date on which this Agreement has changed, UCureMe will treat your use as acceptance of the updated Agreement. </p>
<h2 class="smallheadings">Choice of Law; Jurisdiction; Forum</h2>
<p class="headcontent">Any dispute, controversy or difference which may arise between the parties out of, in relation to or in connection with this Agreement is hereby irrevocably submitted to the exclusive jurisdiction of the courts of Canada, to the exclusion of any other courts without giving effect to its conflict of laws provisions or your actual state or country of residence. </p>
<h2 class="smallheadings">Term</h2>
<p class="headcontent">This Agreement will remain in full force and effect while you use the Website. You may terminate your membership at any time for any reason by following the instructions on the "TERMINATION OF ACCOUNT" in the setting page. We may terminate your membership for any reason at any time. Even after your membership is terminated, certain sections of this Agreement will remain in effect. </p>
  </div>
      <div style="clear:both"></div>
        </div>
        </div>
              
      
</div>
<div class="bottom_wrapper">
	<div class="bottom_footer">
    	<h2></h2>
       
    </div>
   <?php include "footer_new.php"; ?>
</div>
</body>
</html>
