import 'package:equatable/equatable.dart';
import 'creative.dart';
import 'placement.dart';
import 'targeting_rule.dart';

class AdGroup extends Equatable {
  const AdGroup({
    required this.id,
    required this.campaignId,
    required this.name,
    required this.status,
    required this.bidStrategy,
    required this.bidAmount,
    this.creatives = const [],
    this.placements = const [],
    this.targetingRules = const [],
  });

  factory AdGroup.fromJson(Map<String, dynamic> json) {
    return AdGroup(
      id: json['id'] as int,
      campaignId: json['campaign_id'] as int,
      name: json['name'] as String,
      status: json['status'] as String,
      bidStrategy: json['bid_strategy'] as String,
      bidAmount: (json['bid_amount'] as num).toDouble(),
      creatives: (json['creatives'] as List<dynamic>? ?? [])
          .map((c) => Creative.fromJson(c as Map<String, dynamic>))
          .toList(),
      placements: (json['placements'] as List<dynamic>? ?? [])
          .map((p) => Placement.fromJson(p as Map<String, dynamic>))
          .toList(),
      targetingRules: (json['targeting_rules'] as List<dynamic>? ?? [])
          .map((t) => TargetingRule.fromJson(t as Map<String, dynamic>))
          .toList(),
    );
  }

  final int id;
  final int campaignId;
  final String name;
  final String status;
  final String bidStrategy;
  final double bidAmount;
  final List<Creative> creatives;
  final List<Placement> placements;
  final List<TargetingRule> targetingRules;

  Map<String, dynamic> toJson() => {
        'id': id,
        'campaign_id': campaignId,
        'name': name,
        'status': status,
        'bid_strategy': bidStrategy,
        'bid_amount': bidAmount,
        'creatives': creatives.map((c) => c.toJson()).toList(),
        'placements': placements.map((p) => p.toJson()).toList(),
        'targeting_rules': targetingRules.map((t) => t.toJson()).toList(),
      };

  @override
  List<Object?> get props => [
        id,
        campaignId,
        name,
        status,
        bidStrategy,
        bidAmount,
        creatives,
        placements,
        targetingRules,
      ];
}
