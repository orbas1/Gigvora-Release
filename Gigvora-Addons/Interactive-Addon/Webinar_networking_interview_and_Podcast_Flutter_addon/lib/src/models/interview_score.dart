import 'model_utils.dart';

class InterviewScore {
  final int id;
  final int interviewId;
  final int interviewSlotId;
  final int interviewerId;
  final List<dynamic> criteria;
  final List<dynamic> scores;
  final String? comments;
  final DateTime? createdAt;
  final DateTime? updatedAt;

  InterviewScore({
    required this.id,
    required this.interviewId,
    required this.interviewSlotId,
    required this.interviewerId,
    required this.criteria,
    required this.scores,
    this.comments,
    this.createdAt,
    this.updatedAt,
  });

  factory InterviewScore.fromJson(Map<String, dynamic> json) {
    return InterviewScore(
      id: json['id'] as int,
      interviewId: json['interview_id'] as int,
      interviewSlotId: json['interview_slot_id'] as int,
      interviewerId: json['interviewer_id'] as int,
      criteria: List<dynamic>.from(json['criteria'] as List<dynamic>),
      scores: List<dynamic>.from(json['scores'] as List<dynamic>),
      comments: json['comments'] as String?,
      createdAt: parseDateTime(json['created_at']),
      updatedAt: parseDateTime(json['updated_at']),
    );
  }
}

class InterviewScorePayload {
  final List<dynamic> criteria;
  final List<dynamic> scores;
  final String? comments;

  InterviewScorePayload({
    required this.criteria,
    required this.scores,
    this.comments,
  });

  Map<String, dynamic> toJson() => {
        'criteria': criteria,
        'scores': scores,
        'comments': comments,
      };
}
