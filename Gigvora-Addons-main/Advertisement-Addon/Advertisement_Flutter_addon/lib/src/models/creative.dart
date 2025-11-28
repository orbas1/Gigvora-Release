import 'package:equatable/equatable.dart';

class Creative extends Equatable {
  const Creative({
    required this.id,
    required this.campaignId,
    this.adGroupId,
    required this.type,
    required this.headline,
    required this.body,
    this.mediaUrl,
    this.callToAction,
    this.destinationUrl,
    this.status,
  });

  factory Creative.fromJson(Map<String, dynamic> json) {
    return Creative(
      id: json['id'] as int,
      campaignId: json['campaign_id'] as int,
      adGroupId: json['ad_group_id'] as int?,
      type: json['type'] as String,
      headline: (json['headline'] ?? json['title']) as String,
      body: (json['body'] ?? '') as String,
      mediaUrl: (json['media_url'] ?? json['media_path']) as String?,
      callToAction: (json['call_to_action'] ?? json['cta']) as String?,
      destinationUrl: json['destination_url'] as String?,
      status: json['status'] as String?,
    );
  }

  final int id;
  final int campaignId;
  final int? adGroupId;
  final String type;
  final String headline;
  final String body;
  final String? mediaUrl;
  final String? callToAction;
  final String? destinationUrl;
  final String? status;

  Map<String, dynamic> toJson() => {
        'id': id,
        'campaign_id': campaignId,
        'ad_group_id': adGroupId,
        'type': type,
        'headline': headline,
        'body': body,
        'media_url': mediaUrl,
        'call_to_action': callToAction,
        'destination_url': destinationUrl,
        'status': status,
      };

  @override
  List<Object?> get props => [
        id,
        campaignId,
        adGroupId,
        type,
        headline,
        body,
        mediaUrl,
        callToAction,
        destinationUrl,
        status
      ];
}
