require 'spec_helper'
# Compare results of rendering pages via rails and via the old php app

describe PoliciesController do
  include HTMLCompareHelper
  fixtures :electorates, :offices, :members, :member_infos, :divisions, :division_infos, :whips, :votes

  it "#index" do
    compare("/policies.php")
  end
end
