import 'package:equatable/equatable.dart';
import 'ad_group.dart';

class Campaign extends Equatable {
  const Campaign({
    required this.id,
    required this.advertiserId,
    required this.name,
    required this.objective,
    required this.status,
    required this.budget,
    required this.dailyBudget,
    required this.lifetimeBudget,
    this.bidding,
    this.placement,
    this.spend,
    required this.startDate,
    required this.endDate,
    this.adGroups = const [],
  });

  factory Campaign.fromJson(Map<String, dynamic> json) {
    final adGroupsJson = json['ad_groups'] as List<dynamic>? ?? [];
    final start = DateTime.parse(json['start_date'] as String);
    final end = DateTime.parse(json['end_date'] as String);
    final budget = (json['budget'] ?? json['lifetime_budget'] ?? json['daily_budget'] ?? 0) as num;
    final totalBudget = budget.toDouble();
    final durationDays = end.difference(start).inDays == 0 ? 1 : end.difference(start).inDays;
    final daily = (json['daily_budget'] as num?)?.toDouble() ?? (totalBudget / durationDays);
    final lifetime = (json['lifetime_budget'] as num?)?.toDouble() ?? totalBudget;
    return Campaign(
      id: json['id'] as int,
      advertiserId: json['advertiser_id'] as int,
      name: (json['name'] ?? json['title']) as String,
      objective: (json['objective'] ?? '') as String,
      status: (json['status'] ?? 'draft') as String,
      budget: totalBudget,
      dailyBudget: daily,
      lifetimeBudget: lifetime,
      bidding: json['bidding'] as String?,
      placement: json['placement'] as String?,
      spend: (json['spend'] as num?)?.toDouble(),
      startDate: start,
      endDate: end,
      adGroups: adGroupsJson
          .map((group) => AdGroup.fromJson(group as Map<String, dynamic>))
          .toList(),
    );
  }

  final int id;
  final int advertiserId;
  final String name;
  final String objective;
  final String status;
  final double budget;
  final double dailyBudget;
  final double lifetimeBudget;
  final String? bidding;
  final String? placement;
  final double? spend;
  final DateTime startDate;
  final DateTime endDate;
  final List<AdGroup> adGroups;

  Map<String, dynamic> toJson() => {
        'id': id,
        'advertiser_id': advertiserId,
        'title': name,
        'name': name,
        'objective': objective,
        'status': status,
        'budget': budget,
        'daily_budget': dailyBudget,
        'lifetime_budget': lifetimeBudget,
        'placement': placement,
        'bidding': bidding,
        'spend': spend,
        'start_date': startDate.toIso8601String(),
        'end_date': endDate.toIso8601String(),
        'ad_groups': adGroups.map((g) => g.toJson()).toList(),
      };

  Campaign copyWith({
    int? id,
    int? advertiserId,
    String? name,
    String? objective,
    String? status,
    double? budget,
    double? dailyBudget,
    double? lifetimeBudget,
    String? bidding,
    String? placement,
    double? spend,
    DateTime? startDate,
    DateTime? endDate,
    List<AdGroup>? adGroups,
  }) {
    return Campaign(
      id: id ?? this.id,
      advertiserId: advertiserId ?? this.advertiserId,
      name: name ?? this.name,
      objective: objective ?? this.objective,
      status: status ?? this.status,
      budget: budget ?? this.budget,
      dailyBudget: dailyBudget ?? this.dailyBudget,
      lifetimeBudget: lifetimeBudget ?? this.lifetimeBudget,
      bidding: bidding ?? this.bidding,
      placement: placement ?? this.placement,
      spend: spend ?? this.spend,
      startDate: startDate ?? this.startDate,
      endDate: endDate ?? this.endDate,
      adGroups: adGroups ?? this.adGroups,
    );
  }

  @override
  List<Object?> get props => [
        id,
        advertiserId,
        name,
        objective,
        status,
        budget,
        dailyBudget,
        lifetimeBudget,
        bidding,
        placement,
        spend,
        startDate,
        endDate,
        adGroups,
      ];
}
