require 'spec_helper'
# Compare results of rendering pages via rails and via the old php app

describe MembersController do
  include HTMLCompareHelper

  before :each do
    m = Member.create!(first_name: "Tony", last_name: "Abbott", party: "Liberal Party",
      constituency: "Warringah", house: "commons",
      gid: "", source_gid: "", title: "")
    # TODO don't know what aye_majority does yet
    MemberInfo.create!(mp_id: m.id, rebellions: 0, tells: 0, votes_possible: 1, votes_attended: 0, aye_majority: 0)

    m = Member.create!(first_name: "Kevin", last_name: "Rudd", party: "Australian Labor Party",
      constituency: "Griffith", house: "commons",
      gid: "", source_gid: "", title: "")
    # TODO don't know what aye_majority does yet
    MemberInfo.create!(mp_id: m.id, rebellions: 0, tells: 0, votes_possible: 1, votes_attended: 1, aye_majority: -1)

    m = Member.create!(first_name: "Christine", last_name: "Milne", party: "Australian Greens",
      constituency: "Tasmania", house: "lords",
      gid: "", source_gid: "", title: "")
    # TODO don't know what aye_majority does yet
    MemberInfo.create!(mp_id: m.id, rebellions: 0, tells: 0, votes_possible: 1, votes_attended: 1, aye_majority: -1)
  end

  it "#index" do
    compare("/mps.php")
    compare("/mps.php?sort=lastname")
    compare("/mps.php?sort=constituency")
    compare("/mps.php?sort=party")
    compare("/mps.php?sort=rebellions")
    compare("/mps.php?sort=attendance")

    compare("/mps.php?house=senate")
    compare("/mps.php?house=senate&sort=lastname")
    compare("/mps.php?house=senate&sort=constituency")
    compare("/mps.php?house=senate&sort=party")
    compare("/mps.php?house=senate&sort=rebellions")
    compare("/mps.php?house=senate&sort=attendance")
  end
end
