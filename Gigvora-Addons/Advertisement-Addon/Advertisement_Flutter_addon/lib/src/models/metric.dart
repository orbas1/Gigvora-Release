import 'package:equatable/equatable.dart';

class Metric extends Equatable {
  const Metric({
    required this.campaignId,
    required this.impressions,
    required this.clicks,
    required this.conversions,
    required this.spend,
    required this.recordedAt,
  });

  factory Metric.fromJson(Map<String, dynamic> json) {
    return Metric(
      campaignId: json['campaign_id'] as int,
      impressions: json['impressions'] as int,
      clicks: json['clicks'] as int,
      conversions: json['conversions'] as int,
      spend: (json['spend'] as num).toDouble(),
      recordedAt: DateTime.parse(json['recorded_at'] as String),
    );
  }

  final int campaignId;
  final int impressions;
  final int clicks;
  final int conversions;
  final double spend;
  final DateTime recordedAt;

  double get ctr => impressions == 0 ? 0 : (clicks / impressions) * 100;
  double get cpc => clicks == 0 ? 0 : spend / clicks;
  double get cpa => conversions == 0 ? 0 : spend / conversions;
  double get cpm => impressions == 0 ? 0 : (spend / impressions) * 1000;

  Map<String, dynamic> toJson() => {
        'campaign_id': campaignId,
        'impressions': impressions,
        'clicks': clicks,
        'conversions': conversions,
        'spend': spend,
        'recorded_at': recordedAt.toIso8601String(),
      };

  @override
  List<Object?> get props => [
        campaignId,
        impressions,
        clicks,
        conversions,
        spend,
        recordedAt,
      ];
}
