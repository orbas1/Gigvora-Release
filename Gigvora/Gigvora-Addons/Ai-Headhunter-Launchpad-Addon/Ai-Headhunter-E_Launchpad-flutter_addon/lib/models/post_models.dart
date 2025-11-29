class Reaction {
  final int userId;
  final int targetId;
  final String targetType;
  final String type;

  const Reaction({
    required this.userId,
    required this.targetId,
    required this.targetType,
    required this.type,
  });

  factory Reaction.fromJson(Map<String, dynamic> json) {
    return Reaction(
      userId: json['user_id'] as int? ?? json['userId'] as int,
      targetId: json['target_id'] as int? ?? json['targetId'] as int,
      targetType: json['target_type'] as String? ?? json['targetType'] as String? ?? 'post',
      type: json['type'] as String? ?? 'like',
    );
  }

  Map<String, dynamic> toJson() => {
        'user_id': userId,
        'target_id': targetId,
        'target_type': targetType,
        'type': type,
      };
}

class ReactionScore {
  final int userId;
  final int likeScore;
  final int dislikeCount;
  final Map<String, int> breakdown;

  const ReactionScore({
    required this.userId,
    required this.likeScore,
    required this.dislikeCount,
    this.breakdown = const {},
  });

  factory ReactionScore.fromJson(Map<String, dynamic> json) {
    return ReactionScore(
      userId: json['user_id'] as int? ?? json['userId'] as int,
      likeScore: json['like_score'] as int? ?? json['total'] as int? ?? 0,
      dislikeCount: json['dislike_count'] as int? ?? 0,
      breakdown: (json['reaction_breakdown'] as Map<String, dynamic>? ??
                  json['breakdown'] as Map<String, dynamic>?)
              ?.map((key, value) => MapEntry(key, (value as num).toInt())) ??
          const {},
    );
  }

  Map<String, dynamic> toJson() => {
        'user_id': userId,
        'like_score': likeScore,
        'dislike_count': dislikeCount,
        'breakdown': breakdown,
      };
}

class PostPoll {
  final int id;
  final String question;
  final List<PollOption> options;

  const PostPoll({
    required this.id,
    required this.question,
    this.options = const [],
  });

  factory PostPoll.fromJson(Map<String, dynamic> json) {
    return PostPoll(
      id: json['id'] as int,
      question: json['question'] as String? ?? '',
      options: (json['options'] as List? ?? [])
          .map((e) => PollOption.fromJson(e as Map<String, dynamic>))
          .toList(),
    );
  }

  Map<String, dynamic> toJson() => {
        'id': id,
        'question': question,
        'options': options.map((e) => e.toJson()).toList(),
      };
}

class PollOption {
  final int id;
  final String text;
  final int votes;

  const PollOption({
    required this.id,
    required this.text,
    this.votes = 0,
  });

  factory PollOption.fromJson(Map<String, dynamic> json) {
    return PollOption(
      id: json['id'] as int,
      text: json['text'] as String? ?? json['option'] as String? ?? '',
      votes: json['votes'] as int? ?? 0,
    );
  }

  Map<String, dynamic> toJson() => {
        'id': id,
        'text': text,
        'votes': votes,
      };
}

class Thread {
  final int id;
  final String? title;
  final List<int> postIds;

  const Thread({
    required this.id,
    this.title,
    this.postIds = const [],
  });

  factory Thread.fromJson(Map<String, dynamic> json) {
    return Thread(
      id: json['id'] as int,
      title: json['title'] as String?,
      postIds: List<int>.from(json['post_ids'] as List? ?? json['posts'] as List? ?? const []),
    );
  }

  Map<String, dynamic> toJson() => {
        'id': id,
        if (title != null) 'title': title,
        'post_ids': postIds,
      };
}

class Reshare {
  final int originalPostId;
  final String? comment;

  const Reshare({
    required this.originalPostId,
    this.comment,
  });

  factory Reshare.fromJson(Map<String, dynamic> json) {
    return Reshare(
      originalPostId: json['original_post_id'] as int? ?? json['post_id'] as int,
      comment: json['comment'] as String?,
    );
  }

  Map<String, dynamic> toJson() => {
        'original_post_id': originalPostId,
        if (comment != null) 'comment': comment,
      };
}

class CelebratePost {
  final int id;
  final String occasion;
  final DateTime? date;
  final String? message;

  const CelebratePost({
    required this.id,
    required this.occasion,
    this.date,
    this.message,
  });

  factory CelebratePost.fromJson(Map<String, dynamic> json) {
    return CelebratePost(
      id: json['id'] as int,
      occasion: json['occasion'] as String? ?? '',
      date: json['date'] != null ? DateTime.tryParse(json['date'].toString()) : null,
      message: json['message'] as String?,
    );
  }

  Map<String, dynamic> toJson() => {
        'id': id,
        'occasion': occasion,
        if (date != null) 'date': date!.toIso8601String(),
        if (message != null) 'message': message,
      };
}
