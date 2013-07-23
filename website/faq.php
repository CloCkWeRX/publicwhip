<?php require_once "common.inc";
require_once "db.inc";

# $Id: faq.php,v 1.95 2010/12/11 15:05:01 publicwhip Exp $

# The Public Whip, Copyright (C) 2003 Francis Irving and Julian Todd
# This is free software, and you are welcome to redistribute it under
# certain conditions.  However, it comes with ABSOLUTELY NO WARRANTY.
# For details see the file LICENSE.html in the top level of the source.
$paddingforanchors = true; $title = "Help - Frequently Asked Questions"; pw_header();

$db = new DB(); 

?>

<p>
<ul>
<li><a href="#whatis">What is the Public Whip?</a> </li>
<li><a href="#jargon">First, can you explain "division" and other political jargon?</a> </li>
<li><a href="#how">How does the Public Whip work?</a> </li>
<li><a href="#timeperiod">What time period does it cover?</a> </li>

<br>
<li><a href="#clarify">What do the "rebellion" and "attendance" figures mean exactly?</a> </li>
<li><a href="#freevotes">Why do you incorrectly say people are rebels in free votes?</a> </li>
<li><a href="#ayemajority">Why do you refer to Majority and Minority instead of Aye and No?</a> </li>
<li><a href="#policies">What are Policies and how do they work?</a> </li>

<br>
<li><a href="#legal">Legal question, what can I use this information for?</a> </li>
<li><a href="#playwith">Can I play with the software?</a> </li>
<li><a href="#datalicense">What license is the data under?</a> </li>

<br>
<li><a href="#organisation">What organisation is behind the Public Whip?</a> </li>

<br>
<li><a href="#help">Can I help with the project?</a> </li>
<li><a href="#motionedit">What do you mean by editing the motion description?</a> </li>
<!-- <li><a href="#keepup">How can I keep up with what you are doing?</a> </li> -->
<li><a href="#contact">There's something wrong with your webpage / I've found an error / Your wording is dreadfully unclear / Can I make a suggestion?</a> </li>
</ul>
</p>



<h2 class="faq"><a name="whatis">What is the Public Whip?</a></h2>
<p>Public Whip is a project to watch Members of the Australian
Parliament, so that the public (people like us) can better understand and
influence their voting patterns.  We're an independent, non-governmental
project of the charity OpenAustralia Foundation.


<h2 class="faq"><a name="jargon">First, can you explain "division" and other political jargon?</a></h2>
<p>The houses of parliament <b>divide</b> several times each week into members 
who vote <b>Aye</b> (yes, for the motion) and those who vote <b>No</b> (against the
motion).</p>

<p>Each political party has <b>whips</b> who try to persuade their
members to vote for the party line.

<p>An MP <b>rebels</b>, otherwise known as <b>crossing the floor</b>, by voting against the party whip.</b></p>

<p>A <b>teller</b> is an MP involved in the counting of the vote.</p>

<p>For more information on all these terms, see the
<a href="http://www.aph.gov.au/About_Parliament/House_of_Representatives/Powers_practice_and_procedure/00_-_Infosheets/Infosheet_14_-_Making_decisions_-_debate_and_division">
Parliament infosheet on debates and divisions</a>.

<h2 class="faq"><a name="how">How does the Public Whip work?</a></h2>
<p>Debate transcripts of the House of Representatives and the Senate are <a href="http://www.aph.gov.au/">published online</a>. We've written
a program to read them for you and separate out all the records of voting.  This
information has been web-scraped into an online database which you can
access.

<h2 class="faq"><a name="timeperiod">What time period does it cover?</a></h2>
<p>Voting and membership data for MPs extends back to 2006. New divisions usually appear in
Public Whip the next morning, but sometimes take a day or two longer.  We give
no warranty for the data; there may be factual inaccuracies.  Let us know if you find any.

<?php
    require_once "db.inc";
    require_once "parliaments.inc";
    global $pwpdo;

    $div_count=$pwpdo->get_single_row('SELECT COUNT(*) as div_count FROM pw_division',array());
    $mp_count=$pwpdo->get_single_row('select count(distinct pw_mp.person) AS mp_count from pw_mp',array());
    $vote_count=$pwpdo->get_single_row('select count(*) AS vote_count from pw_vote',array());
    $vote_per_div = round($vote_count['vote_count'] / $div_count['div_count'], 1);
    $parties=$pwpdo->fetch_all_rows('select count(*) from pw_mp group by party',array());
    $parties=count($parties);
    $rebellious_votes=$pwpdo->get_single_row('select sum(rebellions) AS rebellions from pw_cache_mpinfo',array());
    $rebelocity = round(100 * $rebellious_votes['rebellions'] / $vote_count['vote_count'], 2);
    $attendance = round(100 * $vote_count['vote_count'] / $div_count['div_count'] / ($mp_count['mp_count'] / parliament_count()), 2);

?>

<p><b>Numerics:</b> The database contains <strong><?php echo number_format($mp_count['mp_count'])?></strong>
distinct Representatives and Senators from <strong><?php echo $parties?></strong> parties who have voted across
<strong><?php echo number_format($div_count['div_count'])?></strong> divisions.
In total <strong><?php echo number_format($vote_count['vote_count'])?></strong> votes were cast
giving an average of <strong><?php echo $vote_per_div?></strong> per division.
Of these <strong><?php echo number_format($rebellious_votes['rebellions'])?></strong> were against the majority vote for
their party giving an average rebellion rate of <strong><?php echo $rebelocity?>%</strong>.


<h2 class="faq"><a name="clarify">What do the "rebellion" and "attendance" figures mean exactly?</a></h2>

<p>The apparent meaning of the data can be misleading, so do not to
jump to conclusions until you have understood it.

<p>"Attendance" is for voting or telling in divisions. An politician may have a
low attendance because they have abstained, have ministerial or
other duties or they are the speaker.  Perhaps they consider each division
carefully, and only vote when they know about the subject. 

Note also that the Public
Whip does not currently record if a member spoke in the debate but did
not vote.

<p>"Rebellion" on this website means a vote against the majority vote by
members of the MP's party.  Unfortunately this will indicate that many members
have rebelled in a free vote.  Until precise data on when and how strongly each
party has whipped is made available, there is no true way of identifying a
"rebellion".  We know of no heuristics which can reliably detect free votes.
See also the <a href="#freevotes">question about free votes</a>.

<h2 class="faq"><a name="freevotes">Why do you incorrectly say people are rebels in free votes?</a></h2>

<p>There is no official, public data about the party whip.  At the moment
we guess based on the majority vote by Representatives or Senators for each party.  In order to
correctly identify rebels, we need to know each party's whip in each division.
There are two ways this could be officially recorded.

<ol>
<li>Hansard clerks could record the whip.  They could either be officially told
the whip by each party's whips' office, or they could deduce it from the
presence of offical whips.  The whip would then be written in Hansard next to
the division listing.
<li>Each whips' office could publish their official whip on their website after
each vote.  If you are a member of a political party, and want to fix the Public
Whip site, lobby them to do this, then let us know.
</ol>

<h2 class="faq"><a name="ayemajority">Why do you refer to Majority and Minority instead of Aye and No?</a> </h2>
<p>Whether a vote is an Aye or a No is less informative than it seems, because it depends 
exactly on the words of the question put (for example: "Motion that the amendment be made" 
versus "Motion that the original words shall stand"), as well as the meaning of the amendment 
which itself carries the possibility of a further negation by its use of words 
("insert the clause" versus "delete the clause").</p>

<p>In truth it would be less confusing if the votes were between "Option (a)" and "Option (b)"
with their meanings clearly expressed.  Indeed, this form of words in the motion text 
has been tried out, as in "Those voting No wanted this, and the Ayes wanted that", 
but then you have to know which side won in order to determine what happened.</p>

<p>But we don't need to express it like that, because all the votes are in the past and 
we always know which side won, and it's the winning side that determines what happens, 
as opposed to what could have happened.  (What could have happened does matter, because if 
it was an alternative version of the law that turned out to be better in the long run 
than what was chosen, then it ought to reflect on the quality of the judgment of the MPs
who were in the minority.)</p>

<p>Accordingly, in many of the explanations and lists we say Majority and Minority because it 
gives a clearer picture of what happened, even 
though the words are less easy to understand than the often misleading "Aye" and "No".</p>

<h2 class="faq"><a name="policies">What are Policies and how do they work?</a></h2>

<p>On Public Whip, a Policy is a set of votes that represent a view on a
particular issue.  They can be used to automatically measure the voting
characteristics of a particular Representative or Senator 
without the need to examine and compare the votes individually.</p>

<p>You do not have to agree with a Policy to have a valid opinion
about the clarity of its description or choice of votes.
This is why we've based their maintenance on a <a href="http://en.wikipedia.org/wiki/Wiki">Wiki</a>
where everyone who is logged in can edit them.  This means that when a Policy
gets out of date, for example new votes have appeared that it should be voting
on, it's up to anyone who sees it to fix it.  It also means you can make
a new policy yourself. </p>

<p>Policies are intended be a tool for checking the voting behaviour of a
Representative or Senator, on top of the ability to read their individual votes.  They
provide nothing more than a flash summary of the data, a summary which you can
drill down through to get to the raw evidence.</p>


<h2 class="faq"><a name="legal">Legal question, what can I use this information for?</a></h2>

<p>Anything, as long as you share it.  The software that runs this site is free open source. The data is licensed under an open data license.  
See the next two questions for details. </p>

<p>Amongst other things, this means that if you use it, you should
double check the information.  It may be wrong.  If you are going to rely on it,
at the very least do some random cross-checking to make sure it is valid.
Whichever way, use it at your own risk.  Of course we'd rather you helped us fix the
software and correct any contact.
See the answer to <a href="#contact">I've found an error</a> for details.</p>

<p>If you reproduce this information, or derive any interesting results from
it, you must refer your readers to this site.  This way they
can use and contribute themselves.</p>


<h2 class="faq"><a name="playwith">Can I play with the software?</a></h2>

<p> Sure.  All the software we've written is free (libre and gratuit), protected by the <a href="http://www.fsf.org/licensing/licenses/agpl-3.0.html">GNU Affero General Public License</a> (which means you can use it and change it, but you have to release any changes you make).  It's not complicated,
anyone can have a go running them. It's available for <a href="https://github.com/openaustralia/publicwhip">download on Github</a>.</p>

<h2 class="faq"><a name="datalicense">What license is the data under?</a></h2>

<p> To the extent which we have rights to this database of MPs voting records 
and related information, it is licensed under the 
<a href="http://opendatacommons.org/licenses/odbl/">Open Data Commons Open Database License</a>.
This is an attribution, share-alike license. That means that you must credit the Public
Whip, for example via a link, if you use the data. It also means that if you build
on this data, you must also share the result under a compatible open data license.
</p>

<h2 class="faq"><a name="organisation">What organisation is behind the Public Whip?</a></h2>
<p>Public Whip in Australia was started and is run by the OpenAustralia Foundation, a charity. It is based on the UK site which was created by 
<a href="http://www.flourish.org">Francis Irving</a> and <a
href="http://www.goatchurch.org.uk">Julian Todd</a> in 2003.</p>

<h2 class="faq"><a name="help">Can I help with the project?</a></h2>
<p>Sure! We're looking for people who are interested in editing the motion descriptions on some of the divisions. See the following question for details. If you have particular skill-sets that you feel you could contribute, then do please email us at team@publicwhip.org.uk.</p>



<h2 class="faq"><a name="motionedit">What do you mean by editing the motion description?</a></h2>

<p>When there is a division in Parliament, it is not always easy to see what it means. Quite often you have to scan through all of the debate in which the division took place (looking for the phrase "I move"), and have a good knowledge of the the jargon to work it out. Also, many votes are about making changes in other documents (eg "to leave out line 5 on page 13 of the Ordinary Persons Pensions Bill") which needs to be found and made available through a link.</p>
<p>The Public Whip software isn't currently sophisticated enough to do this automatically, and it requires help from a person like you. You can find out more about it on our <a href="/project/research.php">Research page</a>, where there is a page of ideas on how to do it.</p>

<!--
<h2 class="faq"><a name="keepup">How can I keep up with what you are doing?</a></h2>
<p>There's the <a href="http://blog.publicwhip.org.uk/">blog</a> and our <a href="http://www.facebook.com/pages/Public-Whip/199268083464697">Facebook page</a>, or you can ask us questions and get updates via and our <a href="http://twitter.com/publicwhip">Twitter account</a>.</p>
-->

<h2 class="faq"><a name="contact">There's something wrong with your webpage / I've found an error / Your wording is dreadfully unclear / Can I make a suggestion?</a></h2>

<p>You can contact us via email at team@publicwhip.org.uk or our <a href="http://twitter.com/openaustralia">Twitter account</a>.</p>

<?php pw_footer() ?>


