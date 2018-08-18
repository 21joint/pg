import "../../components/leader/leader.scss";
import {getLeaders} from "../../middleware/api.service";
import {renderLeader} from "../../components/leader/leader";

(function ($) {
  getLeaders({
    container: "#featuredLeaderboard",
    type: "leader"
  }, function (leaders) {
    $.each(leaders, function (i, leader) {
      let _leaderHtml = renderLeader(leader, {type: "leader"});

      $("#communityLeaders").append(_leaderHtml);
      let _cEl = $(_leaderHtml).find(".leader");
      _cEl.addClass("member-loading");
      setTimeout(function () {
        _cEl.removeClass("member-loading");
      }, i * 100);
    });
  });
})(jQuery);
