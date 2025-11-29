import 'package:equatable/equatable.dart';

class Forecast extends Equatable {
  const Forecast({
    required this.campaignId,
    required this.estimatedImpressions,
    required this.estimatedClicks,
    required this.estimatedConversions,
    required this.estimatedSpend,
    this.assumptions,
  });

  factory Forecast.fromJson(Map<String, dynamic> json) {
    return Forecast(
      campaignId: json['campaign_id'] as int,
      estimatedImpressions: (json['estimated_impressions'] ?? json['reach']) as int,
      estimatedClicks: json['estimated_clicks'] as int? ?? json['clicks'] as int? ?? 0,
      estimatedConversions: json['estimated_conversions'] as int? ?? json['conversions'] as int? ?? 0,
      estimatedSpend: (json['estimated_spend'] as num).toDouble(),
      assumptions: json['assumptions'] as Map<String, dynamic>?,
    );
  }

  final int campaignId;
  final int estimatedImpressions;
  final int estimatedClicks;
  final int estimatedConversions;
  final double estimatedSpend;
  final Map<String, dynamic>? assumptions;

  Map<String, dynamic> toJson() => {
        'campaign_id': campaignId,
        'estimated_impressions': estimatedImpressions,
        'estimated_clicks': estimatedClicks,
        'estimated_conversions': estimatedConversions,
        'estimated_spend': estimatedSpend,
        'assumptions': assumptions,
      };

  @override
  List<Object?> get props => [
        campaignId,
        estimatedImpressions,
        estimatedClicks,
        estimatedConversions,
        estimatedSpend,
        assumptions,
      ];
}
